<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_caas_config_form.
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
 

class scfp_caas_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_caas_config.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_caas_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
     
      'scfp_caas_config.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_caas_config.settings');



   
    $overview = $config->get('scfp_caas_text', '');
    $form['scfp_cass_title'] = array(
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#default_value' => $config->get('scfp_cass_title'),
        '#maxlength' => 255,
      );
   
      $form['scfp_caas_text'] = array(
        '#type' => 'text_format',
        '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
        '#title' => t('Description'),
        '#default_value' => isset($overview['value']) ? $overview['value'] : '',
        '#rows' => 20,
      );
      $form['actions']['save'] = array(
        '#type' => 'submit',
        '#value' => t('Save Changes'),
        // '#submit' => array('_scfp_caas_form_submit'),
      );
      return $form;




  }

    

  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
    // dd($form_state);
    // $this->config('scfp_caas_config.settings')
    // ->set('scfp_cass_title',$form_state->getValue('scfp_cass_title'))
    // ->set('scfp_caas_text',$form_state->getValue('scfp_caas_text'))
 
  
    // ->save();

    foreach ($form_state->getValues() as $key => $value) {
      $this->config('scfp_caas_config.settings')
          ->set($key, $value)->save();
  
    }
    \Drupal::messenger()->addMessage('Form has been saved successfully');
     

    // $val = $form_state['values'];
    // alt_vars_set('scfp_cass_title', $val['scfp_cass_title']);
    // alt_vars_set('scfp_caas_text', trim($val['scfp_caas_text']['value']));
    // alt_vars_set('scfp_caas_text_format', $val['scfp_caas_text']['format']);



    
    }












  
}














