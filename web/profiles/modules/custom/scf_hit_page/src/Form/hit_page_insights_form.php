<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageInsightsForm.
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
 
class hit_page_insights_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'hit_page_insighs.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_insights_form';
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
 

    // // drupal_set_title('HIT Insights');
    // $cat_markup = '<ul>';
    // $cat_markup .= '<li>' . ('Overview', 'admin/hit-page/overview') . '</li>';
    // $cat_markup .= '<li>' . ('Insights', 'admin/hit-page/overview/insights') . '</li>';
    // $cat_markup .= '<li>' . ('HIT process', 'admin/hit-page/overview/hit-process') . '</li>';
    // $cat_markup .= '</ul>';
    // $form['resource_category'] = [
    //   '#type' => 'item',
    //   '#markup' => $cat_markup,
    // ];
    $hit_insight_descrition_1 =  $config->get('hit_insight_descrition_1', '');
    $hit_insight_descrition_2 =  $config->get('hit_insight_descrition_2', '');
    $hit_insight_descrition_3 =  $config->get('hit_insight_descrition_3', '');
  
 

    $form['hit_page_insight_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('hit_page_insight_title'),
    );
  
    // $form['items']['#tree'] = TRUE;
    // $form['items'][0] = [


     

     $form ['hit_insight_title_1'] = [
        '#type' => 'textfield',
        '#default_value' => $config->get('hit_insight_title_1'),
        '#size' => 60,
        '#maxlength' => 255,
        '#required' => FALSE,
      ];
      // $hit_insight_descrition_1 = $config->get('$hit_insight_descrition_1', '');
      // dd($config->get('hit_insight_descrition_1'));
      $form['hit_insight_descrition_1'] = [
        '#type' => 'text_format',
        '#format' => isset($hit_insight_descrition_1['format']) ? $hit_insight_descrition_1['format'] : 'filtered_html',
        '#title' => t('Description'),
        '#default_value' => isset($hit_insight_descrition_1['value']) ? $hit_insight_descrition_1['value'] : '',
        // '#default_value' =>$config->get('hit_insight_descrition_1'),
        '#rows' => 6,
      ];
      $form['hit_insight_title_2'] = [
        '#type' => 'textfield',
        '#default_value' => $config->get('hit_insight_title_2'),
        '#size' => 60,
        '#maxlength' => 255,
        '#required' => FALSE,
      ];
      $form['hit_insight_descrition_2'] =[
        '#type' => 'text_format',
        '#format' => isset($hit_insight_descrition_2['format']) ? $hit_insight_descrition_2['format'] : 'filtered_html',
       '#title' => t('Description'),
        '#default_value' => isset($hit_insight_descrition_2['value']) ? $hit_insight_descrition_2['value'] : '',
        '#rows' => 6,
      ];
      $form['hit_insight_title_3'] =[
        '#type' => 'textfield',
        '#default_value' => $config->get('hit_insight_title_3'),
        '#size' => 60,
        '#maxlength' => 255,
        '#required' => FALSE,
      ];
      $form['hit_insight_descrition_3'] = [
        '#type' => 'text_format',
        '#format' => isset($hit_insight_descrition_3['format']) ? $hit_insight_descrition_3['format'] : 'filtered_html',
        '#title' => t('Description'),
        '#default_value' => isset($hit_insight_descrition_3['value']) ? $hit_insight_descrition_3['value'] : '',
        '#rows' => 6,
      ];
    // ];
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
      // '#submit' => array('_hit_page_insight_form'),
    );

    return $form;
  }





  public function validateForm(array &$form, FormStateInterface $form_state) {

}
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // dd($form_state);


    $this->config(static::SETTINGS)
    // Set the submitted configuration setting.
    ->set('hit_page_insight_title',$form_state->getValue('hit_page_insight_title'))

    ->set('hit_insight_title_1', $form_state->getValue('hit_insight_title_1'))
    ->set('hit_insight_title_2', $form_state->getValue('hit_insight_title_2'))
    ->set(  'hit_insight_title_3', $form_state->getValue('hit_insight_title_3'))
    ->set( 'hit_insight_descrition_1',$form_state->getValue('hit_insight_descrition_1'))
    ->set( 'hit_insight_descrition_2',$form_state->getValue('hit_insight_descrition_2'))
    ->set( 'hit_insight_descrition_3',$form_state->getValue('hit_insight_descrition_3'))
    ->save();
    
    // parent::submitForm($form, $form_state);
          \Drupal::messenger()->addMessage ('Form has been saved successfully');


//     $val = $form_state->getValue;
//     dd(  $val );

//     data_set('hit_page_insight_title', $val['hit_page_insight_title']);
//     data_set('hit_insight_title_1', $val['items']['0']['hit_insight_title_1']);
//     data_set('hit_insight_title_2', $val['items']['0']['hit_insight_title_2']);
//     data_set('hit_insight_title_3', $val['items']['0']['hit_insight_title_3']);
//     data_set('hit_insight_descrition_1', $val['items']['0']['hit_insight_descrition_1']);
//     data_set('hit_insight_descrition_2', $val['items']['0']['hit_insight_descrition_2']);
//     data_set('hit_insight_descrition_3', $val['items']['0']['hit_insight_descrition_3']);  
//  \Drupal::message()->addMessage('Form has been saved successfully');
  
  
  }










}