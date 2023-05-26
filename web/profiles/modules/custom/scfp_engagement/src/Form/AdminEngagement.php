<?php
/**
 * @file
 * Contains \Drupal\scfp_engagement\Form\AdminEngagement.
 */
namespace Drupal\scfp_engagement\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for adminengagement Config Form.
 */
class AdminEngagement extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'AdminEngagement';
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
        $form['engagement_creation_tooltips'] = [
        '#type' => 'textfield',
        '#title' => 'Engagement creation tooltips',
        '#required' => true,
        '#default_value' => $config->get('engagement_creation_tooltips'),
        ];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save configuration'),
        );

        return $form;

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
    public function openid_connect_auto_login_get_all_roles()
    {
        $roles = \Drupal::entityQuery('user_role')
            ->condition('status', 1)
            ->execute();
        return $roles;
    }
}
