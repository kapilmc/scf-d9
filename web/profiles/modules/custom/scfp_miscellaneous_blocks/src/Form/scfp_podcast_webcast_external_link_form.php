<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_podcast_webcast_external_link_form.
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
 

class scfp_podcast_webcast_external_link_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_podcast_webcast_external.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_podcast_webcast_external_link_form';
  }
   /**  ̰
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      // static::SETTINGS,
      'scfp_podcast_webcast_external.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_podcast_webcast_external.settings');

    //   drupal_set_title('Podcast/Webcast/Events External Links');
      $form['scfp_podcast_external_link_title'] = array(
        '#type' => 'textfield',
        '#title' => t('Podcast external link title'),
        '#default_value' => $config->get('scfp_podcast_external_link_title', ''),
        '#required' => FALSE,
        );
      $form['scfp_podcast_external_link'] = array(
        '#type' => 'textfield',
        '#title' => t('Podcast external link'),
        '#default_value' => $config->get('scfp_podcast_external_link', ''),
        '#maxlength' => 512,
        '#required' => FALSE,
        );
    
      $form['scfp_webcast_external_link_title'] = array(
        '#type' => 'textfield',
        '#title' => t('Webcast external link title'),
        '#default_value' => $config->get('scfp_webcast_external_link_title', ''),
        '#maxlength' => 512,
        '#required' => FALSE,
        );
      $form['scfp_webcast_external_link'] = array(
        '#type' => 'textfield',
        '#title' => t('Webcast external link'),
        '#default_value' => $config->get('scfp_webcast_external_link', ''),
        '#maxlength' => 512,
        '#required' => FALSE,
        );
    
        $form['scfp_events_external_link_title'] = array(
          '#type' => 'textfield',
          '#title' => t('Events external link title'),
          '#default_value' => $config->get('scfp_events_external_link_title', ''),
          '#maxlength' => 512,
          '#required' => FALSE,
          );
        $form['scfp_events_external_link'] = array(
          '#type' => 'textfield',
          '#title' => t('Events external link'),
          '#default_value' => $config->get('scfp_events_external_link', ''),
          '#maxlength' => 512,
          '#required' => FALSE,
          );




    // return $form;
    return parent::buildForm($form, $form_state);


  }


  public function validateForm(array &$form, FormStateInterface $form_state) {

    // $field_link = '';
    // $field_link_title = '';
    
    if(!empty($form_state->getValue('scfp_podcast_external_link'))) {
      

      $field_link = $form_state->getValue('scfp_podcast_external_link');

      $field_link_title =$form_state->getValue('scfp_podcast_external_link_title');

      $field_link = strtolower(trim($field_link));
      if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
        $form_state->setErrorByName('scfp_podcast_external_link', 'Invalid link. Please enter url with http or https.');
      }
    }
  
    if(!empty($form_state->getValue('scfp_webcast_external_link'))) {
      

        $field_link = $form_state->getValue('scfp_webcast_external_link');
        $field_link_title =$form_state->getValue('scfp_webcast_external_link_title');
    
        $field_link = strtolower(trim($field_link));

        if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
          $form_state->setErrorByName('scfp_webcast_external_link', 'Invalid link. Please enter url with http or https.');
        }
      }


    if(!empty($form_state->getValue('scfp_events_external_link'))) {
      

        $field_link = $form_state->getValue('scfp_events_external_link');
        $field_link_title =$form_state->getValue('scfp_events_external_link_title');
    
        $field_link = strtolower(trim($field_link));

        if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
          $form_state->setErrorByName('scfp_events_external_link', 'Invalid link. Please enter url with http or https.');
        }
      }


}

public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('scfp_podcast_webcast_external.settings')

    ->set('scfp_podcast_external_link_title',$form_state->getValue('scfp_podcast_external_link_title'))
    ->set('scfp_podcast_external_link',$form_state->getValue('scfp_podcast_external_link'))
    ->set('scfp_webcast_external_link_title',$form_state->getValue('scfp_webcast_external_link_title'))

    ->set('scfp_webcast_external_link',$form_state->getValue('scfp_webcast_external_link'))
    ->set('scfp_events_external_link_title',$form_state->getValue('scfp_events_external_link_title'))
    ->set('scfp_events_external_link',$form_state->getValue('scfp_events_external_link'))
  
    ->save();
    parent::submitForm($form, $form_state);

    }






}





