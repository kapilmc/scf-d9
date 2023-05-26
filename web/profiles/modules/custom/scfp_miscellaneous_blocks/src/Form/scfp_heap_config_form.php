<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_heap_config_form
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
 

class scfp_heap_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_heap_config.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_heap_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
     
      'scfp_heap.sttings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_heap.sttings');


    

        $form['scfp_heap_id'] = array(
            '#type' => 'textfield',
            '#title' => t('Heap ID'),
            '#default_value' => $config->get('scfp_heap_id', ''),
            '#maxlength' => 128,
            '#required' => FALSE,
            );
        
        
    //   return system_settings_form($form);

    $form['actions']['save'] = array(
        '#type' => 'submit',
        '#value' => t('Save congiguration'),
      
      );

    return $form;


  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('scfp_heap.sttings')
 
    ->set('scfp_heap_id',$form_state->getValue('scfp_heap_id'))
  
  
    ->save();

    \Drupal::messenger()->addMessage('The configuration options have been saved.');
     
    
    }
  











}











