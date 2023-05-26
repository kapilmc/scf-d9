<?php
/**
 * @file
 * Contains \Drupal\openid_connect_auto_login\Form\OpenidConnectForm.
 */
namespace Drupal\openid_connect_auto_login\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for OpenidConnectAutoLogin Config Form.
 */
class OpenidConnectForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'OpenidConnectForm';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['openid.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('openid.settings');
        $form['traversed_paths'] = [
            '#type' => 'fieldset',
            '#title' => t('Traversed paths'),
            '#collapsible' => true,
        ];

        $form['traversed_paths']['openid_connect_auto_login_excluded_paths'] = [
            '#type' => 'textarea',
            '#title' => t('Excluded paths'),
            '#size' => 20,
            '#description' => t('Admin login to hit url:example.com/user/login or set Specify the paths to exclude for the autologin in the field Excluded paths field example: /user/login, /node/1, etc.'),
            '#default_value' => $config->get('openid_connect_auto_login_excluded_paths'),
        ];

        $form['select_roles'] = [
            '#type' => 'fieldset',
            '#title' => t('select roles'),
            '#collapsible' => true,
        ];

        $all_roles = $this->openid_connect_auto_login_get_all_roles();
        $form['select_roles']['openid_connect_auto_login_user_roles'] = [
            '#type' => 'checkboxes',
            '#title' => t('Choose a role for auto login user register'),
            '#options' => $all_roles,
            '#default_value' => $config->get('openid_connect_auto_login_user_roles'),
        ];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save configuration'),
        );

        return $form;

    }
    public function openid_connect_auto_login_get_all_roles()
    {
        $roles = \Drupal::entityQuery('user_role')
            ->condition('status', 1)
            ->execute();
        return $roles;
    }

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('openid.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }
    }

}