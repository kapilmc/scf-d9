<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\key_document_config_form
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
 

class key_document_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'key_document_config.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'key_document_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      
      'key_document.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('key_document.settings');

       $form['scfp_key_document_title'] = array(
            '#type' => 'textfield',
            '#title' => t('Key document title'),
            '#default_value' => $config->get('scfp_key_document_title', ''),
            '#maxlength' => 255,
            '#required' => FALSE,
            );
          $form['scfp_key_document_nid'] = array(
            '#type' => 'textfield',
            '#title' => t('Key document node id'),
            '#default_value' => $config->get('scfp_key_document_nid', ''),
            '#required' => FALSE,
            );
          $form['scfp_burger_meet_our_people_nid'] = array(
            '#type' => 'textfield',
            '#title' => t('Burger menu meet our people node id'),
            '#default_value' => $config->get('scfp_burger_meet_our_people_nid', ''),
            '#required' => FALSE,
            );
        
          $form['scf_burger_key_documents_and_first_alert_nid'] = array(
            '#type' => 'textfield',
            '#title' => t('Burger menu SCF key documents and first alert node id'),
            '#default_value' => $config->get('scf_burger_key_documents_and_first_alert_nid', ''),
            '#required' => FALSE,
            );
        
          $form['scf_burger_finance_tools_analytical_offerings_nid'] = array(
            '#type' => 'textfield',
            '#title' => t('Burger menu SCF finance tools and analytical offerings node id'),
            '#default_value' => $config->get('scf_burger_finance_tools_analytical_offerings_nid', ''),
            '#required' => FALSE,
            );






            return parent::buildForm($form, $form_state);


  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('key_document.settings')
 
    ->set('scfp_key_document_title',$form_state->getValue('scfp_key_document_title'))

    ->set('scfp_key_document_nid',$form_state->getValue('scfp_key_document_nid'))

    ->set('scfp_burger_meet_our_people_nid',$form_state->getValue('scfp_burger_meet_our_people_nid'))

    ->set('scf_burger_key_documents_and_first_alert_nid',$form_state->getValue('scf_burger_key_documents_and_first_alert_nid'))
    ->set('scf_burger_finance_tools_analytical_offerings_nid',$form_state->getValue('scf_burger_finance_tools_analytical_offerings_nid'))
  
    ->save();

    // \Drupal::messenger()->addMessage('The configuration options have been saved.');
     
    parent::submitForm($form, $form_state);
    }
  











}











