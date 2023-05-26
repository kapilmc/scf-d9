<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_mini_banner_config_form.
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
 

class scfp_mini_banner_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_mini_banner.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_mini_banner_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
    
      'scfp_mini_banner.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_mini_banner.settings');

    




    
    $form['scfp_nini_banner_display'] = array(
        '#type' =>'checkbox',
        '#description' => 'Check this if you do not want to display.',
        '#title' => t('Do not display'),
        '#default_value' => $config->get('scfp_nini_banner_display'),
      );
    
      $form['scfp_nini_banner_text'] = array(
        '#type' => 'textfield',
        '#title' => t('Mini banner text'),
        '#default_value' => $config->get('scfp_nini_banner_text'),
        '#maxlength' => 255,
        '#required' => TRUE,
        );
      $form['scfp_mini_banner_document'] = array(
        '#type' => 'textfield',
        //'#title' => t('Mini banner document id'),
        '#title' => t('Mini banner URL'),
        '#default_value' =>$config->get('scfp_mini_banner_document'),
        '#maxlength' => 255,
       //'#autocomplete_path' => 'admin/mini-banner/autocomplete',
        );
      
      $form['actions']['save'] = array(
        '#type' => 'submit',
        '#value' => t('Save Changes'),
        // '#submit' => array('_scfp_mini_banner_form_submit'),
      );


    return $form;


  }







  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('scfp_mini_banner.settings')

    ->set('scfp_nini_banner_display',$form_state->getValue('scfp_nini_banner_display'))
    ->set('scfp_nini_banner_text',$form_state->getValue('scfp_nini_banner_text'))
    ->set('scfp_mini_banner_document',$form_state->getValue('scfp_mini_banner_document'))
  
    ->save();
    \Drupal::messenger()->addMessage('Form has been saved successfully');
    }






}





