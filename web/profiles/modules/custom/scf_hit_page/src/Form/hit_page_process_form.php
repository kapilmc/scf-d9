<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageProcessForm.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Form\ConfigFormBase;
 
class hit_page_process_form extends  ConfigFormBase {
  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'hit_page_process.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_process_form';
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

    // $cat_markup = '<ul>';
    // $cat_markup .= '<li>' . l('Overview', 'admin/hit-page/overview') . '</li>';
    // $cat_markup .= '<li>' . l('Insights', 'admin/hit-page/overview/insights') . '</li>';
    // $cat_markup .= '<li>' . l('HIT process', 'admin/hit-page/overview/hit-process') . '</li>';
    // $cat_markup .= '</ul>';
    // $form['resource_category'] = [
    //   '#type' => 'item',
    //   '#markup' => $cat_markup,
    // ];
    // $overview = alt_vars_get('hit_page_overview', '');
    $form['hit_page_process_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('hit_page_process_title'),
    );
  
    $form['hit_page_process_file'] = array(
      '#title' => t('Upload Image'),
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#default_value' => $config->get('hit_page_process_file'),
      '#upload_location' => 'public://hit-page/',
      "#upload_validators"  => array("file_validate_extensions" => array("jpg jpeg png")),
      '#description' => '',
    );
    
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
      // '#submit' => array('_hit_page_process_form'),
    );


    return $form;
  }





  public function validateForm(array &$form, FormStateInterface $form_state) {

}
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // dd($form_state);  


// Retrieve the configuration.
$this->config(static::SETTINGS)
// Set the submitted configuration setting.
->set('hit_page_process_file',$form_state->getValue('hit_page_process_file'))

// if ($val['hit_page_process_file'] > 0) {
//   $file = file_load($val['hit_page_process_file']);
//   // Change the status.
//   $file->status = 1;
//   file_usage_add($file, 'file', 'hit_page', 1);
//   // Update the file status into the database.
//    $f = file_save($file);
// }



->set('hit_page_process_title', $form_state->getValue('hit_page_process_title'))
->save();

\Drupal::messenger()->addMessage('Form has been saved successfully');


    // $val = $form_state['values'];

    // alt_vars_set('hit_page_process_file', $val['hit_page_process_file']);

    // if ($val['hit_page_process_file'] > 0) {
    //   $file = file_load($val['hit_page_process_file']);
    //   // Change the status.
    //   $file->status = 1;
    //   file_usage_add($file, 'file', 'hit_page', 1);
    //   // Update the file status into the database.
    //    $f = file_save($file);
    // }

    // alt_vars_set('hit_page_process_title', $val['hit_page_process_title']);

    // \Drupal::messenger()->addMessage('Form has been saved successfully');







  
  
  }

}