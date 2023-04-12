<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageSampleHitsForm.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Form\ConfigFormBase;
 
class hit_page_sample_hits_form extends ConfigFormBase {


  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'hit_page_sample.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_sample_hits_form';
  }
 /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['hit_page_sample_hits.settings'];
    }
  
    /**
     * {@inheritdoc}
     */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
  
    $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
    $config = $this->config('hit_page_sample_hits.settings');

  
    $form['hit_page_sample_hits_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
       '#default_value' =>  $config->get('hit_page_sample_hits_title'),
    
    );
    

  
      $count = $config->get('hit_page_sample_items');
      $count = ($count == '' || $count == 0) ? 0 : $count;
   
      $form['items']['#tree'] = TRUE;
      if (empty($tempstore->get('num_rows_sample')) || $tempstore->get('num_rows_sample') == NULL) {
        $tempstore->set('num_rows_sample', $count);
      }

      for ($i = 0; $i < $tempstore->get('num_rows_sample'); $i++) {
         $remove_path = '/admin/hit-page/sample-hits/remove-sample/'.$i;
       
        $remove_link = 'Remove';
        $links = '';
        $links = '<div class="scf_links_remove_quetes"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
  
     
  
        $form['items'][$i] = [
          'hit_page_sample_hits_item_title' => [
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#default_value' => $config->get('hit_page_sample_hits_item_title'.$i),
            '#size' => 60,
            '#maxlength' => 255,
            '#required' => FALSE,
          ],
          'hit_page_sample_hits_item_descrition' => [
            '#type' => 'textfield',
             '#title' => t('url'),
            '#default_value' => $config->get('hit_page_sample_hits_item_descrition'.$i),
            '#size' => 60,
            '#maxlength' => 255,
            // '#required' => FALSE,
          ],
    
          'links' => [
            '#attributes' => array('class' => array('sample_remove_item')), 
             '#markup' => $links,
           
          ],
  
    
  
  
        ];
  
      }
  
  
      $form['names_fieldset']['actions']['save'] = array(
        '#type' => 'submit',
        '#value' => t('Save Changes'),
      );
     
      $form['names_fieldset']['actions']['add_name'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add another Documents'),
        '#submit' => ['::_hit_page_sample_hits_form_add_more'],
       
      ];

      return $form;

    }


    
    
    
      function _hit_page_sample_hits_form_add_more(array &$form, FormStateInterface $form_state)
      { 
        $config = $this->config('hit_page_sample_hits.settings');
        
        $val = $form_state->getValues();
        $items = [];
       
        foreach($val['items'] as $key => $item) {
          if(!empty($item['hit_page_sample_hits_item_title']) || !empty($item['hit_page_sample_hits_item_descrition'])) {
            $items[] = ['title' => $item['hit_page_sample_hits_item_title'], 'url' => $item['hit_page_sample_hits_item_descrition']];     
          }
        }

        foreach($items as $key => $item) {
          
           // Set the submitted configuration setting.
           $config->set('hit_page_sample_items', count($items));
           $config->set('hit_page_sample_hits_item_title'.$key, $item['title']);
           $config->set('hit_page_sample_hits_item_descrition'.$key, $item['url']);

           
          
           $config->save();
    
        }
        
        
        $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
        
        $tempstore->set('num_rows_sample', $tempstore->get('num_rows_sample') + 1);
        $form_state->setRebuild();
    
        // dd($config);
      }
    






  public function validateForm(array &$form, FormStateInterface $form_state) {
    // $val = $form_state->getValues();
    // $field_link = trim($value['hit_page_sample_hits_item_descrition']);
    // $field_title = trim($value['hit_page_sample_hits_item_title']);

    // if(strlen($form_state->getValue('hit_page_sample_hits_item_title')) ) {
    //   $form_state->setErrorByName('hit_page_sample_hits_item_title', $this->t('Please enter title'));
    // }

    // if(!empty($form_state->getValue('scfp_podcast_external_link'))) {
      

    //   $field_link = $form_state->getValue('scfp_podcast_external_link');

    //   $field_link_title =$form_state->getValue('scfp_podcast_external_link_title');

    //   $field_link = strtolower(trim($field_link));
    //   if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
    //     $form_state->setErrorByName('scfp_podcast_external_link', 'Invalid link. Please enter url with http or https.');
    //   }
    // }
  

    // foreach ($form_state['values']['items'] as $key => $value) {

    // foreach($val as $key => $value) {

    //   dd($value);

    //   $field_link = '';
    //   $field_title = '';

    //   $field_link = trim($value['hit_page_sample_hits_item_descrition']);
    //   $field_title = trim($value['hit_page_sample_hits_item_title']);

    //   if(empty($field_title)) {

    //     $form_state->setErrorByName('items]['.$key.'][hit_page_sample_hits_item_title', 'Please enter title.');
    //   }
    //   if(empty($field_link)) {
    //     $form_state->setErrorByName('items]['.$key.'][hit_page_sample_hits_item_descrition', 'Please enter url with http or https.');
    //   }
    //   if(!empty($field_link)) {
    //     $field_link = strtolower($field_link);
    //     if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
    //       $form_state->setErrorByName('items]['.$key.'][hit_page_sample_hits_item_descrition', 'Invalid link. Please enter url with http or https.');
    //     }
    //   }
    // }

  }
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
 

    $config = $this->config('hit_page_sample_hits.settings');

    $val = $form_state->getValues();
    $items = [];
  

  


    foreach($val['items'] as $key => $item) {
      if(!empty($item['hit_page_sample_hits_item_title']) || !empty($item['hit_page_sample_hits_item_descrition'])) {
        $items[] = ['title' => $item['hit_page_sample_hits_item_title'], 'url' => $item['hit_page_sample_hits_item_descrition']];     
      }
    }
    
    if (!empty($val['hit_page_sample_hits_title'])) {
      $config->set('hit_page_sample_hits_title', $val['hit_page_sample_hits_title']);
    }

    foreach($items as $key => $item) {
   
      
      // Set the submitted configuration setting.
       $config->set('hit_page_sample_items', count($items));
      $config->set('hit_page_sample_hits_item_title'.$key, $item['title']);
      $config->set('hit_page_sample_hits_item_descrition'.$key, $item['url']);
     
      
      $config->save();

    }
 
    \Drupal::messenger()->addMessage('Form has been saved successfully');



  }
}




