<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\factiva_api_config_form
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
 

class factiva_api_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'factiva_api_config.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'factiva_api_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      
      'factiva_api.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('factiva_api.settings');


    $form['scfp_factiva_api_config'] = array(
        '#type' => 'textarea',
        '#title' => t('API'),
        '#default_value' => $config->get('scfp_factiva_api_config', ''),
        '#rows' => 2,
        );
     

        return parent::buildForm($form, $form_state);

  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('factiva_api.settings')
 
    ->set('scfp_factiva_api_config',$form_state->getValue('scfp_factiva_api_config'))
  
  
    ->save();

    // \Drupal::messenger()->addMessage('The configuration options have been saved.');
    parent::submitForm($form, $form_state);
    
    }
  











}











