<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_initiatives_form.
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
 

class scfp_initiatives_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_initiatives.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_initiatives_form';
  }
   /**  ̰
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'scfp_initiatives.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_initiatives.settings');
    // dd($config);

    
        // drupal_set_title('Initiatives configuration');
    
        $form['blocke1'] = array(
          '#type' => 'fieldset',
          '#title' => t('Block first'),
          '#collapsible' => TRUE,
          '#collapsed' => FALSE,
          );
    
        $form['blocke1']['url1'] = array(
          '#type' => 'textfield',
          '#title' => t('Url'),
          
          '#default_value' => $config->get('url1'),
          '#size' => 100,
          '#maxlength' => 255,
          '#description' => t('Title of S&CF initiatives Block which displays under Win Journey'),
        );
      
        $form['blocke1']['image1'] = array(
          '#title' => t('Image'),
          '#type' => 'managed_file',
          '#description' => t('The uploaded image will be displayed on this scf initiatives block.'),
          '#default_value' => $config->get('image1'),
          '#upload_location' => 'public://scfp_initiatives/',
          "#upload_validators"  => array("file_validate_extensions" => array("png jpeg jpg gif")),
        );
    
       
        $form['blocke2'] = array(
          '#type' => 'fieldset',
          '#title' => t('Block second'),
          '#collapsible' => TRUE,
          '#collapsed' => FALSE,
          );
    
        $form['blocke2']['url2'] = array(
          '#type' => 'textfield',
          '#title' => t('Url'),
          '#default_value' => $config->get('url2'),
          '#size' => 100,
          '#maxlength' => 255,
          '#description' => t('Title of S&CF initiatives Block which displays under Win Journey'),
        );
      
        $form['blocke2']['image2'] = array(
          '#title' => t('Image'),
          '#type' => 'managed_file',
          '#description' => t('The uploaded image will be displayed on this scf initiatives block.'),
          '#default_value' => $config->get('image2'),
          '#upload_location' => 'public://scfp_initiatives/',
          "#upload_validators"  => array("file_validate_extensions" => array("png jpeg jpg gif")),
        );

        $form['blocke3'] = array(
          '#type' => 'fieldset',
          '#title' => t('Block third'),
          '#collapsible' => TRUE,
          '#collapsed' => FALSE,
          );
    
        $form['blocke3']['url3'] = array(
          '#type' => 'textfield',
          '#title' => t('Url'),
          '#default_value' => $config->get('url3'),
          '#size' => 100,
          '#maxlength' => 255,
          '#description' => t('Title of S&CF initiatives Block which displays under Win Journey'),
        );
      
        $form['blocke3']['image3'] = array(
          '#title' => t('Image'),
          '#type' => 'managed_file',
          '#description' => t('The uploaded image will be displayed on this scf initiatives block.'),
          '#default_value' => $config->get('image3'),
          '#upload_location' => 'public://scfp_initiatives/',
          "#upload_validators"  => array("file_validate_extensions" => array("png jpeg jpg gif")),
        );









    // return $form;
    return parent::buildForm($form, $form_state);


  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

   

}

public function submitForm(array &$form, FormStateInterface $form_state) {
  $val = $form_state->getValues();
  // dd($val);
  // ->set('image1', $val['image1']);
    $this->config('scfp_initiatives.settings')
    // ->set('image1',$form_state->getValue('image1'))
    ->set('image1', $val['image1'])
    ->set('image2',$val['image1'])
    ->set('image3',$val['image1'])

//     ->set('image1', $val['image1']);
// if ($val['image1'] > 0) {
//   $file = File::load($val['image1']);
//   // Change the status.
//   $file->status = 1;
//   file_usage_add($file, 'file', 'scfp_initiatives', 1);
//   // Update the file status into the database.
//   $f = file_save($file);
// }

    // if(isset($val['image1'])) {
    //   // Set file status to permanent.
    //   $image = $val('image1');
    //   $file = File::load($image[0]);
    //   $file->setPermanent();
    //   $file->save();
    //   // Add to file usage calculation.
    //   \Drupal::service('file.usage')->add;
    // }

    // $this->config('scfp_initiatives.settings')
    //   ->set('image1', $form_state->getValue('image1'))
    //   // ->save();



    ->set('url1', $val['url1'])
    ->set('url2', $val['url3'])
    ->set('url3', $val['url3'])
 

    ->save();
    parent::submitForm($form, $form_state);

    }



}




// if ($val['image1'] > 0) {
//   $file = file_load($val['image1']);
//   // Change the status.
//   $file->status = 1;
//   file_usage_add($file, 'file', 'scfp_initiatives', 1);
//   // Update the file status into the database.
//   $f = file_save($file);
// }
// if ($val['image2'] > 0) {
//   $file = file_load($val['image2']);
//   // Change the status.
//   $file->status = 1;
//   file_usage_add($file, 'file', 'scfp_initiatives', 1);
//   // Update the file status into the database.
//   $f = file_save($file);
// }
// if ($val['image3'] > 0) {
//   $file = file_load($val['image2']);
//   // Change the status.
//   $file->status = 1;
//   file_usage_add($file, 'file', 'scfp_initiatives', 1);
//   // Update the file status into the database.
//   $f = file_save($file);
// }
