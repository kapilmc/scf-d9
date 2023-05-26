<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_meet_our_people_tab_form.
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
 

class scfp_meet_our_people_tab_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_meet_our_people.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_meet_our_people_tab_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
   
    return ['scfp_meet_our_people_tab.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
  
    $config = $this->config('scfp_meet_our_people_tab.settings');
   

  $vid = 'expert_category';




  $tag_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  $options = $options = [0 => '<none>'] +[];
  foreach ($tag_terms as $tag_term) {
   $options[$tag_term->tid] = $tag_term->name;
   }





    $form['meet_our_people_default_tab'] = array(
      '#type' => 'select',
      '#title' => t('Default Tab'),
      '#options' => $options,
      '#default_value' => $config->get('meet_our_people_default_tab', 0),

      );
      $form['meet_our_people_first_alert'] = array(
        '#type' => 'select',
        '#title' => t('First Alert'),
        '#options' => $options,
        '#default_value' => $config->get('meet_our_people_first_alert', 0),
        );
    $form['meet_our_people_expert'] = array(
        '#type' => 'select',
        '#title' => t('Experts'),
        '#options' => $options,
        '#default_value' => $config->get('meet_our_people_expert', 0),
        );
  
    $form['meet_our_people_external_advisory'] = array(
        '#type' => 'select',
        '#title' => t('External Advisors'),
        '#options' => $options,
        '#default_value' => $config->get('meet_our_people_external_advisory', 0),
        );


        return parent::buildForm($form, $form_state);



  
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {
//   $val= $form_state->getValues();
// dd($val);

  $this->config('scfp_meet_our_people_tab.settings')



  ->set( 'meet_our_people_default_tab',$form_state->getValue('meet_our_people_default_tab'))
  ->set( 'meet_our_people_first_alert',$form_state->getValue('meet_our_people_first_alert'))
  ->set( 'meet_our_people_expert',$form_state->getValue('meet_our_people_expert'))
  ->set( 'meet_our_people_external_advisory',$form_state->getValue('meet_our_people_external_advisory'))

  ->save();
  parent::submitForm($form, $form_state);



  
}
}