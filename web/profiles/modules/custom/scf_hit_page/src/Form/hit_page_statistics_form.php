<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageStatisticsForm.
 */
namespace Drupal\scf_hit_page\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
 
class hit_page_statistics_form extends ConfigFormBase {
  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'hit_page_statics.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_statistics_form';
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
    $form['hit_page_statistics_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('hit_page_statistics_title'),
    );
    $form['hit_page_statistics'] = array(
      '#type' => 'text_format',
      // '#type' =>'textfield',
      '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
      '#title' => t('Description'),
      // '#default_value' => isset($overview['value']) ? $overview['value'] : '',
      '#default_value' => $config->get('hit_page_statistics'),
      '#rows' => 6,
    );
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
      // '#submit' => array('_hit_page_statistics_form'),
    );
    
    

    return $form;
  }





  public function validateForm(array &$form, FormStateInterface $form_state) {

}
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // dd($form_state->getValue('hit_page_statistics'));
    $this->config(static::SETTINGS)
// Set the submitted configuration setting.
->set('hit_page_statistics_title', $form_state->getValue('hit_page_statistics_title'))
->set('hit_page_statistics', $form_state->getValue('hit_page_statistics'))

->save();

// parent::submitForm($form, $form_state);
      \Drupal::messenger()->addMessage ('Form has been saved successfully');
  }




}