<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageOverviewForm.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Form\ConfigFormBase;
 
class hit_page_overview_form extends ConfigFormBase {
  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'hit_page_overview.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_overview_form';
  }
  
  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
   
      return ['hit_page_overview.settings'];
    
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('hit_page_overview.settings');
 


  
    $overview = $config->get('hit_page_overview', '');
    $form['hit_page_overview_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('hit_page_overview_title'),
    );
    $form['hit_page_overview'] = array(
      '#type' => 'text_format',
      '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
      '#title' => t('Description'),
      '#default_value' => isset($overview['value']) ? $overview['value'] : '',
      '#rows' => 6,
    );

    $form['hit_page_overview_footer_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Footer Text'),
      '#default_value' => $config->get('hit_page_overview_footer_text'),
    );


    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
      // '#submit' => array('_hit_page_overview_form'),
    );
    return $form;

  }


  public function validateForm(array &$form, FormStateInterface $form_state) {

}
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      $this->config('hit_page_overview.settings')
          ->set($key, $value)->save();

      // parent::submitForm($form, $form_state);
      \Drupal::messenger()->addMessage('Form has been saved successfully');
  }
  }







}