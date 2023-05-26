<?php
/**
 * @file
 * Contains \Drupal\scf_mcsi\mcsi_overview_form.
 */
namespace Drupal\scf_mcsi\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;



 

class mcsi_overview_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'overview.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mcsi_overview_form';
  }
  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mcsi_overview.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mcsi_overview.settings');

    // $options = array(
    //   'query'      => ['type' => 'overview form', 'status' => 1],
    //   'fragment'   => 'form-list',
    //   'attributes' => ['class' => ['btn', 'btn-mini']],
    //   'absolute'   => TRUE,
    // );
    // $link_overview = Link::fromTextAndUrl(t('Overview'), Url::fromUri('internal:/admin/mcsi/overview', $options))->toString();
    // $options = array(
    //   'query'      => ['type' => 'Principles form', 'status' => 1],
    //   'fragment'   => 'Principles-list',
    //   'attributes' => ['class' => ['btn', 'btn-mini']],
    //   'absolute'   => TRUE,
    // );
    // $link_principles = Link::fromTextAndUrl(t('Principles'), Url::fromUri('internal:/admin/mcsi/overview/principles', $options))->toString();
    

    // $form['overview_child_tab'] = [
    //   '#markup' => Markup::create('<span class="overview_child_tab">'.$link_overview.'</span>'),
    // ];
    // $form['principal_tab'] = [
    //   '#markup' => Markup::create('<span class="principles_child_tab">'.$link_principles.'</span>'),
    // ];

    
    $overview = $config->get('mcsi_overview', '');
    $form['mcsi_overview_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('mcsi_overview_title'),
    // '#default_value' =>'Overview',
    );
    $form['mcsi_overview'] = array(
      '#type' => 'text_format',
      '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
      '#title' => t('Description'),
      '#default_value' => isset($overview['value']) ? $overview['value'] : '',

      // '#default_value' =>$config->get('mcsi_overview'),
      '#rows' => 6,
    );
  
    $form['mcsi_video_image'] = array(
      '#title' => t('Video Thumbnail Image'),
      '#type' => 'managed_file',
      '#description' => t('Allowed file types: <b>png gif jpg jpeg</b>.'),
    //   '#default_value' => alt_vars_get('mcsi_video_image'),
       '#default_value' => $config->get('mcsi_video_image'),
      '#upload_location' => 'public://mcsi-assets/',
      "#upload_validators"  => array("file_validate_extensions" => array("png jpeg jpg gif")),
    );
    $form['mcsi_video'] = array(
      '#title' => t('Video'),
      '#type' => 'managed_file',
      '#description' => t('Allowed file types: <b>mp4</b>.'),
    //   '#default_value' => alt_vars_get('mcsi_video'),
      '#default_value' => $config->get('mcsi_video'),
      '#upload_location' => 'public://mcsi-assets/',
      "#upload_validators"  => array("file_validate_extensions" => array("mp4")),
    );
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
    //   '#submit' => array('_mcsi_overview_form_submit'),
    );
    return $form;





  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mcsi_overview.settings')

    ->set('mcsi_overview_title',$form_state->getValue('mcsi_overview_title'))
    ->set('mcsi_overview',$form_state->getValue('mcsi_overview'))
    ->set('mcsi_video_image',$form_state->getValue('mcsi_video_image'))
    ->set('mcsi_video',$form_state->getValue('mcsi_video'))
    ->save();

    \Drupal::messenger()->addMessage('Form has been saved successfully');


//     $val= $form_state->getvalues();
//     dd($val);
//     // $val = $form_state['values'];

//     $val = [
//         'mcsi_video_image'=> $form_state->getValue('mcsi_video_image'),
//         'mcsi_video'=> $form_state->getValue('mcsi_video'),
    
//     ];

// // File::load();
//     if ($val['mcsi_video_image'] > 0) {
//       $file = file_load($val['mcsi_video_image']);
      
//       // Change the status.
//       $file->status = 1;
//       file_usage_add($file, 'file', 'mcsi_assets', 1);
//       // Update the file status into the database.
//       $f = file_save($file);
//     }
//     if ($val['mcsi_video'] > 0) {
//       $file = file_load($val['mcsi_video']);
//       // Change the status.
//       $file->status = 1;
//       file_usage_add($file, 'file', 'mcsi_assets', 1);
//       // Update the file status into the database.
//       $f = file_save($file);
//     }
// dd($f);

    // alt_vars_set('mcsi_overview', $val['mcsi_overview']);
    // alt_vars_set('mcsi_overview_title', $val['mcsi_overview_title']);

  //  \Drupal::messenger()->addMessage('Form has been saved successfully');







}
}