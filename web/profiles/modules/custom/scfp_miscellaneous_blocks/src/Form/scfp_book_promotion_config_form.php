<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_book_promotion_config_form
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
 

class scfp_book_promotion_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_book_promotion_config.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_book_promotion_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
     
      'scfp_book_promotion.setting'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_book_promotion.setting');

            $form['scfp_book_promotion_display'] = array(
                '#type' =>'checkbox',
                '#description' => 'Check this if you do not want to display.',
                '#title' => t('Do not display'),
                '#default_value' =>$config->get('scfp_book_promotion_display'),
              );
              $form['scfp_book_promotion_text'] = array(
                '#type' => 'textarea',
                '#title' => t('Book promotion text'),
                '#default_value' => $config->get('scfp_book_promotion_text'),
                );
              $form['scfp_book_promotion_link_text'] = array(
                '#type' => 'textfield',
                '#title' => t('Book promotion link title'),
                '#default_value' => $config->get('scfp_book_promotion_link_text'),
                '#maxlength' => 255,
                );
              $form['scfp_book_promotion_link'] = array(
                '#type' => 'textfield',
                '#title' => t('Book promotion link URL'),
                '#default_value' => $config->get('scfp_book_promotion_link'),
                '#maxlength' => 255,
                );
              $form['scfp_book_promotion_image'] = array(
                '#title' => t('Book promotion image'),
                '#type' => 'managed_file',
                '#default_value' => $config->get('scfp_book_promotion_image'),
                '#upload_location' => 'public://scfp_book_promotion/',
                "#upload_validators"  => array("file_validate_extensions" => array("png gif jpg")),
                );
              $form['scfp_book_promotion_side_image'] = array(
                '#title' => t('Book promotion side image'),
                '#type' => 'managed_file',
                '#default_value' => $config->get('scfp_book_promotion_side_image'),
                '#upload_location' => 'public://scfp_book_promotion/',
                "#upload_validators"  => array("file_validate_extensions" => array("png gif jpg")),
              );
              $form['actions']['save'] = array(
                '#type' => 'submit',
                '#value' => t('Save Changes'),
                // '#submit' => array('_scfp_book_promotion_form_submit'),
              );
            //   $form['#validate'][] = '_scfp_book_promotion_form_validate';
              return $form;









  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

    $field_link = '';
    if(!empty($form_state->getValue('scfp_book_promotion_link'))) {
        $field_link = $form_state->getValue('scfp_book_promotion_link');
        $field_link = strtolower(trim($field_link));
        // dd($field_link);
        if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
            $form_state->setErrorByName('scfp_book_promotion_link', 'Invalid link. Please enter url with http or https.');
          }
       
      }




}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('scfp_book_promotion.setting')
 
    ->set('scfp_book_promotion_display',$form_state->getValue('scfp_book_promotion_display'))

    ->set('scfp_book_promotion_text',$form_state->getValue('scfp_book_promotion_text'))

    ->set('scfp_book_promotion_link_text',$form_state->getValue('scfp_book_promotion_link_text'))

    ->set('scfp_book_promotion_link',$form_state->getValue('scfp_book_promotion_link'))
    ->set('scfp_book_promotion_image',$form_state->getValue('scfp_book_promotion_image'))
    ->set('scfp_book_promotion_side_image',$form_state->getValue('scfp_book_promotion_side_image'))





    // if($form_state->getValue('scfp_book_promotion_image') > 0) {
    //     $file = File::load($form_state->getValue('scfp_book_promotion_image'));
    //     // Change the status
    //     $file->status = 1;
    //     file_usage_add($file, 'file', 'book_promotion', 1);
    //     // Update the file status into the database
    //     file_save($file);
    //   }
    //   if($form_state->getValue('scfp_book_promotion_side_image') > 0) {
    //     $file = File::load($form_state->getValue('scfp_book_promotion_side_image'));
    //     // Change the status
    //     $file->status = 1;
    //     file_usage_add($file, 'file', 'book_promotion', 2);
    //     // Update the file status into the database
    //     file_save($file);
    //   }





    // if(isset($form_state['default_press_release_image'])) {
    //     // Set file status to permanent.
    //     $image = $form_state->getValue('scfp_book_promotion_image');
    //     $file = File::load($image[0]);
    //     $file->setPermanent();
    //     $file->save();
    //     // Add to file usage calculation.
    //     \Drupal::service('file.usage')->add;
    //   }


    //   if(isset($form_state['default_press_release_images'])) {
    //     // Set file status to permanent.
    //     $image = $form_state->getValue('scfp_book_promotion_side_imag');
    //     $file = File::load($image[0]);
    //     $file->setPermanent();
    //     $file->save();
    //     // Add to file usage calculation.
    //     \Drupal::service('file.usage')->add;
    //   }




    //   ->set('default_press_release_image', $form_state->getValue('default_press_release_image'))
    //     ->set('default_press_release_images', $form_state->getValue('default_press_release_images'))
        ->save();
  
    // parent::submitForm($form, $form_state);
  
    // // ->save();

    \Drupal::messenger()->addMessage('Form has been saved successfully');
     
    
    }
  






    // $val = $form_state['values'];
    // alt_vars_set('scfp_book_promotion_display', $val['scfp_book_promotion_display']);
    // alt_vars_set('scfp_book_promotion_text', trim($val['scfp_book_promotion_text']));
    // alt_vars_set('scfp_book_promotion_link_text', trim($val['scfp_book_promotion_link_text']));
    // alt_vars_set('scfp_book_promotion_link', $val['scfp_book_promotion_link']);
    // alt_vars_set('scfp_book_promotion_image', $val['scfp_book_promotion_image']);
    // alt_vars_set('scfp_book_promotion_side_image', $val['scfp_book_promotion_side_image']);
    // if($val['scfp_book_promotion_image'] > 0) {
    //   $file = file_load($val['scfp_book_promotion_image']);
    //   // Change the status
    //   $file->status = 1;
    //   file_usage_add($file, 'file', 'book_promotion', 1);
    //   // Update the file status into the database
    //   file_save($file);
    // }
    // if($val['scfp_book_promotion_side_image'] > 0) {
    //   $file = file_load($val['scfp_book_promotion_side_image']);
    //   // Change the status
    //   $file->status = 1;
    //   file_usage_add($file, 'file', 'book_promotion', 2);
    //   // Update the file status into the database
    //   file_save($file);
    // }
    // drupal_set_message('Form has been saved successfully');




}











