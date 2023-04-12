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
// Retrieve the configuration.
$this->config(static::SETTINGS)
// Set the submitted configuration setting.
->set('hit_page_overview_title',$form_state->getValue('hit_page_overview_title'))
->set('hit_page_overview', $form_state->getValue('hit_page_overview'))
->save();

// parent::submitForm($form, $form_state);
      \Drupal::messenger()->addMessage('Form has been saved successfully');
  
  
  }



// function _hit_page_overview_form($form, &$form_state) {
//   $val = $form_state['values'];
//   alt_vars_set('hit_page_overview', $val['hit_page_overview']);
//   alt_vars_set('hit_page_overview_title', $val['hit_page_overview_title']);
//   drupal_set_message('Form has been saved successfully');
// }






}