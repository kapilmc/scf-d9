<?php
/**
 * @file
 * Contains \Drupal\scf_leading_edge\scf_mck_quaterly_form.
 */
namespace Drupal\scf_leading_edge\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
 

class scf_mck_quaterly_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scf_mck_quaterly.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scf_mck_quaterly_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['scf_mck_quaterly.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scf_mck_quaterly.settings');
   


    $form['scf_mck_quaterly_strategy'] = array(
        '#type' => 'textarea',
        '#title' => t('Strategy Articles URL'),
        '#default_value' =>  $config->get('scf_mck_quaterly_strategy', ''),
        '#required' => TRUE,
        '#rows' => 2,
        );
      $form['scf_mck_quaterly_corp_fin'] = array(
        '#type' => 'textarea',
        '#title' => t('Corporate Finance Articles URL'),
        '#default_value' => $config->get('scf_mck_quaterly_corp_fin', ''),
        '#required' => TRUE,
        '#rows' => 2,
        );
      $form['scf_mck_quaterly_is_published'] = array(
        '#type' => 'checkbox',
        '#title' => t('Published'),
        '#return_value' => 1,
        '#description' => 'If checked articles published on import.',
        '#default_value' =>  $config->get('scf_mck_quaterly_is_published', 0),
        '#required' => FALSE,
        );




        // $form['actions']['save'] = [
        //     '#type' => 'submit',
        //     '#value' => $this->t('Save configuration '),
        //     // '#submit' => array('_mcsi_core_team_form_save'),
           
        // ];
   
    // return $form;
    return parent::buildForm($form, $form_state);
  }



  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {

//   $this->config(static::SETTINGS)

//   ->set( 'scf_mck_quaterly_strategy',$form_state->getValue('scf_mck_quaterly_strategy'))
//   ->set( 'scf_mck_quaterly_corp_fin',$form_state->getValue('scf_mck_quaterly_corp_fin'))

//   ->set( 'scf_mck_quaterly_is_published',$form_state->getValue('scf_mck_quaterly_is_published'))
 
//   ->save();

// //   \Drupal::messenger()->addMessage('Form has been saved successfully');

foreach ($form_state->getValues() as $key => $value) {
  $this->config('scf_mck_quaterly.settings')
      ->set($key, $value)->save();

  parent::submitForm($form, $form_state);
}



  
}
}