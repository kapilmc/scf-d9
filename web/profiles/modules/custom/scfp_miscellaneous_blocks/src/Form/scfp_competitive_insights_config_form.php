<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\Form\scfp_competitive_insights_config_form.
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
 

class scfp_competitive_insights_config_form extends ConfigFormBase {
  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_competitive_insights.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_competitive_insights_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
     
      'scfp_competitive_insights.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_competitive_insights.settings');

    // drupal_set_title('Competitive insights configuration');
  $form['scfp_cmpi_tite'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' =>$config->get('scfp_cmpi_tite'),
    '#maxlength' => 255,
  );
  $form['scfp_cmpi_sub_tite'] = array(
    '#type' => 'textfield',
    '#title' => t('Sub Title'),
    '#default_value' => $config->get('scfp_cmpi_sub_tite'),
    '#maxlength' => 255,
  );
  $overview = $config->get('scfp_cmpi_text', '');
  $form['scfp_cmpi_text'] = array(
    '#type' => 'text_format',
    '#title' => t('Description'),
    '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
    // '#default_value' => $config->get('scfp_cmpi_text'),
    // '#default_value'=> ('scfp_cmpi_text'),
    '#default_value' => isset($overview['value']) ? $overview['value'] : '',
    
    // '#format' =>'filtered_html',
  );


  $form['scfp_cmpi_image'] = array(
    '#title' => t('Competitive insights Image'),
    '#type' => 'managed_file',
    '#default_value' => $config->get('scfp_cmpi_image'),
    '#upload_location' => 'public://competitive_insights/',
    "#upload_validators"  => array("file_validate_extensions" => array("png gif jpg")),
  );

  $form['scfp_cmpi_list_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Listing Title'),
    '#default_value' => $config->get('scfp_cmpi_list_title'),
    '#maxlength' => 255,
  );
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save Changes'),
    // '#submit' => array('_scfp_competitive_insights_form_submit'),
  );


 return $form;
  }



    
    
    
    
    

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $val= $form_state->getvalues();
    // dd($val);
    // $this->config('scfp_competitive_insights.settings')
    // ->set('scfp_cmpi_tite',$form_state->getValue('scfp_cmpi_tite'))
    // ->set('scfp_cmpi_sub_tite',$form_state->getValue('scfp_cmpi_sub_tite'))
    // ->set('scfp_cmpi_tex',$form_state->getValue('scfp_cmpi_tex'))
    // ->set('scfp_cmpi_text_format',$form_state->getValue('scfp_cmpi_text_format'))
    // ->set('scfp_cmpi_image',$form_state->getValue('scfp_cmpi_image'))
    // ->set('scfp_cmpi_list_title',$form_state->getValue('scfp_cmpi_list_title'))
  
    // ->save();
    foreach ($form_state->getValues() as $key => $value) {
      $this->config('scfp_competitive_insights.settings')
          ->set($key, $value)->save();
  
    }


    
    // $val= $form_state->getvalues();
    // dd($val);

    // ->set('scfp_cmpi_tite', $val['scfp_cmpi_tite']);
    // ->set('scfp_cmpi_sub_tite', trim($val['scfp_cmpi_sub_tite']));
    // ->set('scfp_cmpi_text', trim($val['scfp_cmpi_text']['value']));
    // ->set('scfp_cmpi_text_format', trim($val['scfp_cmpi_text']['format']));
    // ->set('scfp_cmpi_image', $val['scfp_cmpi_image']);
    // ->set('scfp_cmpi_list_title', $val['scfp_cmpi_list_title']);
    // ->save();

    // if ($val['scfp_cmpi_image'] > 0) {
    //   $file = file_load($val['scfp_cmpi_image']);
    //   // Change the status.
    //   $file->status = 1;
    //   file_usage_add($file, 'file', 'competitive_insights', 1);
    //   // Update the file status into the database.
    //   file_save($file);
    // }
//   ->save();

   \Drupal::messenger()->addMessage('The configuration options have been saved');





  }















}