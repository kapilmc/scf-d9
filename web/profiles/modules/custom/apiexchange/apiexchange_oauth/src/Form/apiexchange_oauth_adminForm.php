<?php
/**
 * Contains \Drupal\apiexchange_oauth\Form\apiexchange_oauth_adminForm.
 * 
 * @param string apiexchange_oauth
 */
namespace Drupal\apiexchange_oauth\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;

class apiexchange_oauth_adminForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'apiexchnageid';
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['apiexchange_oauth_url'] = array(
        '#type' => 'textfield',
        '#title' => t('API Oauth Url'),
        '#required' => true,
        '#maxlength' => 255,
        // '#default_value' => variable_get('apiexchange_oauth_url', ''),
        );
    
        $form['apiexchange_oauth_grant_type'] = array(
        '#type' => 'textfield',
        '#title' => t('API Grant Type'),
        '#required' => true,
        '#maxlength' => 255,
        // '#default_value' => variable_get('apiexchange_oauth_grant_type', ''),
        );
    
        $form['apiexchange_oauth_scope'] = array(
        '#type' => 'textfield',
        '#title' => t('API Oauth Scope'),
        '#required' => true,
        '#maxlength' => 255,
        // '#default_value' => variable_get('apiexchange_oauth_scope', ''),
        );
    
        $form['apiexchange_oauth_client_id'] = array(
        '#type' => 'textfield',
        '#title' => t('API Oauth Client Id'),
        '#required' => true,
        '#maxlength' => 255,
        // '#default_value' => variable_get('apiexchange_oauth_client_id', ''),
        );
    
        $form['apiexchange_oauth_client_secret'] = array(
        '#type' => 'textfield',
        '#title' => t('API Oauth Client Secret'),
        '#required' => true,
        '#maxlength' => 255,
        // '#default_value' => variable_get('apiexchange_oauth_client_secret', ''),
        );
    
        $form['apiexchange_oauth_debug_mode'] = array(
        '#title' => t('Debug mode'),
        '#description' => t('Enable debug mode if you would like to see the URL when a request is being executed.'),
        '#type' => 'checkbox',
        // '#default_value' => variable_get('apiexchange_oauth_debug_mode', ''),
        );

        $form['submit'] = array(
          '#type' => 'submit',
          '#value' => t('Save configuration')
          );
    
        return ($form);

    }

    // public function validateForm(array $form, FormStateInterface $form_state)
    // {

    // }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    
        // foreach (Element::children($form) as $variable) {
        //     $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
        //   }
        //   $config->save();
      
        //   if (method_exists($this, '_submitForm')) {
        //     $this->_submitForm($form, $form_state);
        //   }
      
        //   parent::submitForm($form, $form_state);

    }
}
