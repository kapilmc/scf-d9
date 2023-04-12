<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageContactForm.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
 
class hit_page_contact_form extends ConfigFormBase {
/** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'hit_page.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_contact_form';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
   
   

    $form['hit_page_contact_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' =>$config->get('hit_page_contact_title'), 
      
    );
    $form['hit_page_contact_desc'] = array(
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#default_value' => $config->get('hit_page_contact_desc'),
    );
  
    $form['hit_page_contact_link_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Link Text'),
     
      '#default_value' => $config->get('hit_page_contact_link_text'),

    );
    $form['hit_page_contact_link_type'] = array(
      '#type' => 'select',
      '#options' => ['url' => 'URL', 'email' => 'Email'],
      '#title' => t('Link Type'),
   
      '#default_value' => $config->get('hit_page_contact_link_type'),
    );
  
    $form['hit_page_contact_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Link/Email'),
      '#default_value' => $config->get('hit_page_contact_link'),
    );
    // $form['#validate'][] = 'hit_conatct_validate_link';
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
     
    );
    return $form;
    // return parent::buildForm($form, $form_state);

  }





  public function validateForm(array &$form, FormStateInterface $form_state) {

    $value = $form_state->getValue('hit_page_contact_link');






  //   // $val = $form_state['values'];
  // $field_link = trim($value['hit_page_contact_link']);
  // if (!empty($field_link)) {
  //   if ($value['hit_page_contact_link_type'] == 'url') {
  //     if (!empty($field_link) && (substr($field_link, 0, 7) != 'http://') && (substr($field_link, 0, 8) != 'https://')) {
  //       setErrorByName('hit_page_contact_link_type', 'Please enter url with http or https.');
  //     }
  //   }

    // if ($val['hit_page_contact_link_type'] == 'email') {
    //   if (!empty($field_link) &&  !valid_email_address($field_link)) {
    //     setErrorByName('hit_page_contact_link_type', 'Please enter valid email.');
    //   }
    // }
  // }

}
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {




// Retrieve the configuration.
$this->config(static::SETTINGS)
// Set the submitted configuration setting.
->set('hit_page_contact_title',$form_state->getValue('hit_page_contact_title'))
->set('hit_page_contact_desc', $form_state->getValue('hit_page_contact_desc'))
->set('hit_page_contact_link_text', $form_state->getValue('hit_page_contact_link_text'))
->set(  'hit_page_contact_link_type', $form_state->getValue('hit_page_contact_link_type'))
->set( 'hit_page_contact_link',$form_state->getValue('hit_page_contact_link'))
->save();

// parent::submitForm($form, $form_state);
      \Drupal::messenger()->addMessage ('Form has been saved successfully');
  }

}