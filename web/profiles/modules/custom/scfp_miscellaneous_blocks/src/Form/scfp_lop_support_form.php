<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_lop_support_form.
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
 

class scfp_lop_support_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_lop_support.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_lop_support_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['scfp_lop_support.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('scfp_lop_support.settings');

    // drupal_set_title('S&CF LOP Support');
    $form['scfp_lop_title'] = array(
      '#type' => 'textfield',
      '#title' => t('SCFP Block Title'),
      '#default_value' =>  $config->get('scfp_lop_title', 'S&CF LOP support'),
      '#size' => 100,
      '#maxlength' => 255,
      '#description' => t('Title of S&CF Lop Block which displays under Win Journey'),
    );
    $form['scfp_lop_description'] = array(
      '#type' => 'textarea',
      '#title' => t('SCFP Block Description '),
      '#default_value' =>  $config->get('scfp_lop_description', 'Get Help from the LOP Support Team'),
      '#description' => t('Description of S&CF Lop Block along with the email for the support'),
      '#rows' => 2,
      '#format' => 'filtered_html',
    );
    $form['scfp_lop_image'] = array(
      '#title' => t('Image'),
      '#type' => 'managed_file',
      '#description' => t('The uploaded image will be displayed on this scf lop block.'),
      '#default_value' =>  $config->get('scfp_lop_image', ''),
      '#upload_location' => 'public://scfp_lop_image/',
    );
    // return $form;





    return parent::buildForm($form, $form_state);


    //     $form['actions']['save'] = [
    //         '#type' => 'submit',
    //         '#value' => $this->t('Save configuration '),
    //         // '#submit' => array('_mcsi_core_team_form_save'),
           
    //     ];
   
    // return $form;
  }



  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {

  // $this->config(static::SETTINGS)

  // ->set( 'scfp_lop_title',$form_state->getValue('scfp_lop_title'))
  // ->set( 'scfp_lop_description',$form_state->getValue('scfp_lop_description'))
  // ->set( 'scfp_lop_image',$form_state->getValue('scfp_lop_image'))

  // ->save();

//   \Drupal::messenger()->addMessage('Form has been saved successfully');

  foreach ($form_state->getValues() as $key => $value) {
    $config = $this->config('scfp_lop_support.settings')
        ->set($key, $value)->save();

    parent::submitForm($form, $form_state);
}

  // $this->config(static::SETTINGS)


  
}
}