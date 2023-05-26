<?php
/**
 * @file
 * Contains \Drupal\scf_leading_edge\scf_leading_edge_form.
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
 

class scf_leading_edge_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scf_leading.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scf_leading_edge_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['scf_leading.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scf_leading.settings');
    $form['scf_leading_edge_sftp_host'] = array(
        '#type' => 'textfield',
        '#title' => t('SFTP Host'),
        // '#default_value' => variable_get('scf_leading_edge_sftp_host', ''),
          '#default_value' => $config->get('scf_leading_edge_sftp_host', ''),
        '#maxlength' => 512,
        '#required' => TRUE,
        );
      $form['scf_leading_edge_sftp_port'] = array(
        '#type' => 'textfield',
        '#title' => t('SFTP Port'),
        '#default_value' => $config->get('scf_leading_edge_sftp_port', ''),
        '#required' => TRUE,
        );
      $form['scf_leading_edge_sftp_name'] = array(
        '#type' => 'textfield',
        '#title' => t('SFTP User Name'),
        '#default_value' => $config->get('scf_leading_edge_sftp_name', ''),
        '#required' => TRUE,
        );
      $form['scf_leading_edge_sftp_pwd'] = array(
        '#type' => 'textfield',
        '#title' => t('SFTP Password'),
        '#default_value' => $config->get('scf_leading_edge_sftp_pwd', ''),
        '#required' => TRUE,
        );
      // $form['scf_leading_edge_sftp_dir'] = array(
      //   '#type' => 'textfield',
      //   '#title' => t('SFTP Directory'),
      //   '#default_value' => variable_get('scf_leading_edge_sftp_dir', ''),
      //   '#required' => TRUE,
      //   );
      $form['scf_leading_edge_sftp_filename'] = array(
        '#type' => 'textfield',
        '#title' => t('SFTP Filename'),
        '#default_value' => $config->get('scf_leading_edge_sftp_filename', ''),
        '#required' => TRUE,
        );
      $form['scf_leading_edge_sftp_discipline'] = array(
        '#type' => 'textfield',
        '#title' => t('Discipline'),
        '#description' => 'Enter ";" seprated discipline to import data.',
        '#default_value' => $config->get('scf_leading_edge_sftp_discipline', ''),
        '#required' => TRUE,
        );
      $form['scf_leading_edge_is_published'] = array(
        '#type' => 'checkbox',
        '#title' => t('Published'),
        '#return_value' => 1,
        '#description' => 'If checked articles published on import.',
        '#default_value' => $config->get('scf_leading_edge_is_published', 0),
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

  // $this->config('scf_leading.settings')

  // ->set( 'scf_leading_edge_sftp_host',$form_state->getValue('scf_leading_edge_sftp_host'))
  // ->set( 'scf_leading_edge_sftp_port',$form_state->getValue('scf_leading_edge_sftp_port'))
  // ->set( 'scf_leading_edge_sftp_name',$form_state->getValue('scf_leading_edge_sftp_name'))
  // ->set( 'scf_leading_edge_sftp_pwd',$form_state->getValue('scf_leading_edge_sftp_pwd'))
  // ->set( 'scf_leading_edge_sftp_filename',$form_state->getValue('scf_leading_edge_sftp_filename'))
  // ->set( 'scf_leading_edge_sftp_discipline',$form_state->getValue('scf_leading_edge_sftp_discipline'))
  // ->set( 'scf_leading_edge_is_published',$form_state->getValue('scf_leading_edge_is_published'))
 
  // ->save();

//   \Drupal::messenger()->addMessage('Form has been saved successfully');

foreach ($form_state->getValues() as $key => $value) {
  $this->config('scf_leading.settings')
      ->set($key, $value)->save();

  parent::submitForm($form, $form_state);
}


  
}
}