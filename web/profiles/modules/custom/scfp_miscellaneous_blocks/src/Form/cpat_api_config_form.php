<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\cpat_api_config_form
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
 

class cpat_api_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'cpat_api_config.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cpat_api_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
     
      'cpat_api_config.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cpat_api_config.settings');




    $form['scfp_cpat_api_token'] = array(
        '#type' => 'textfield',
        '#title' => t('API Token'),
        '#default_value' => $config->get('scfp_cpat_api_token', ''),
        '#maxlength' => 512,
        '#required' => TRUE,
        );
      $form['scfp_cpat_api_env'] = array(
        '#type' => 'textfield',
        '#title' => t('Enviroment'),
        '#default_value' => $config->get('scfp_cpat_api_env', ''),
        '#required' => TRUE,
        );
      $form['scfp_cpat_api_charge_code'] = array(
        '#type' => 'textfield',
        '#title' => t('Charge Code'),
        '#default_value' => $config->get('scfp_cpat_api_charge_code', ''),
        '#required' => TRUE,
        );
    
  
   return parent::buildForm($form, $form_state);


  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('cpat_api_config.settings')
    // $val= $form_state->getvalues();

    // dd($val);

    ->set('scfp_cpat_api_token',$form_state->getValue('scfp_cpat_api_token'))
    ->set('scfp_cpat_api_env',$form_state->getValue('scfp_cpat_api_env'))
    ->set('scfp_cpat_api_charge_code',$form_state->getValue('scfp_cpat_api_charge_code'))
  
    ->save();

    parent::submitForm($form, $form_state);
     
    
    }
  











}











