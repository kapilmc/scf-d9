<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\cdh_page_reach_out_form.
 */
namespace Drupal\cdh_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for cdh_page_reach_out_form Config Form.
 */
class cdh_page_reach_out_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'cdh_page_reach_out_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_reach_out.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cdh_page_reach_out.settings');
          $form['cdh_page_reach_out_title'] = array(
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#default_value' => $config->get('cdh_page_reach_out_title'),
          );
          $form['text'] = array(
            '#type' => 'textfield',
            '#title' => t('Text'),
            '#default_value' => $config->get('text'),
          );

          $form['links'] = array(
            '#type' => 'textfield',
            '#title' => t('Links'),
            '#default_value' => $config->get('links'),
          );

          $form['actions']['save'] = array(
            '#type' => 'submit',
            '#value' => t('Save Changes'),
            // '#submit' => array('_cdh_page_reach_out_form'),
          );

          return $form;

    }
    

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('cdh_page_reach_out.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }
    }
}
