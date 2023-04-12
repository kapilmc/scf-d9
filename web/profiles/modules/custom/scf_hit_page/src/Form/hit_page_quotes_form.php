<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\PitPageQuotesForm.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\user\PrivateTempStoreFactory;
 
class hit_page_quotes_form extends ConfigFormBase {


  protected function getEditableConfigNames() {
    return [
      'hit_page_quotes.settings',
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_quotes_config_form';
  }

  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);

   
    $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
    

    $config = $this->config('hit_page_quotes.settings');

    $form['hit_page_quotes_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('hit_page_quotes_title'),
    );
    
  
    $count = $config->get('hit_page_quotes_items');
    $count = ($count == '' || $count == 0) ? 0 : $count;
    
    $form['items']['#tree'] = TRUE;
    if (empty($tempstore->get('num_rows')) || $tempstore->get('num_rows') == NULL) {
    
      $tempstore->set('num_rows', $count);
    }
 
    for ($i = 0; $i < $tempstore->get('num_rows'); $i++) {
       $remove_path = '/admin/hit-page/quotes/remove-quotes/'.$i;
     
      $remove_link = 'Remove';
      $links = '';
      $links = '<div class="scf_links_remove_quetes"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
      $overview = [];
      $overview = $config->get('hit_page_quotes_item_descrition_'.$i, '');

      $form['items'][$i] = [
        'hit_page_quotes_item_title' => [
          '#type' => 'textfield',
          '#title' => t('Title'),
          '#default_value' => $config->get('hit_page_quotes_item_title_'.$i),
          '#size' => 60,
          '#maxlength' => 255,
          '#required' => FALSE,
        ],
        'hit_page_sub_title' => [
          '#type' => 'textfield',
           '#title' => t('Sub Title'),
          '#default_value' => $config->get('hit_page_sub_title_'.$i),
          '#size' => 60,
          '#maxlength' => 255,
          // '#required' => FALSE,
        ],
        'hit_page_quotes_item_descrition' => [
          '#type' => 'text_format',
          '#title' => t('Descrition'),
          '#default_value' => isset($overview['value']) ? $overview['value'] : '',
          '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
          '#rows' => 6,
        ],
        'links' => [
          //'#type' => 'button',
          // '#title' => $this->t('Link title'),
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
      '#value' => $this->t('Add another quote'),
      '#submit' => ['::_hit_page_quotes_form_add_more'],
     
    ];
  //  dd($form);
    return $form;
  }




  function _hit_page_quotes_form_add_more(array &$form, FormStateInterface $form_state)
  { 
    $config = $this->config('hit_page_quotes.settings');
    
    $val = $form_state->getValues();
    $items = [];
   
    foreach($val['items'] as $key => $item) {
      if(!empty($item['hit_page_quotes_item_title']) || !empty($item['hit_page_sub_title']) ||  !empty($item['hit_page_quotes_item_descrition']['value'])) {
        $items[] = ['title' => $item['hit_page_quotes_item_title'], 'sub_title' => $item['hit_page_sub_title'], 'text' =>  $item['hit_page_quotes_item_descrition']];     
      }
    }

    foreach($items as $key => $item) {
      
       // Set the submitted configuration setting.
       $config->set('hit_page_quotes_items', count($items));
       $config->set('hit_page_quotes_item_title_'.$key, $item['title']);
       $config->set('hit_page_sub_title_'.$key, $item['sub_title']);
       $config->set('hit_page_quotes_item_descrition_'.$key, $item['text']);
       
      
       $config->save();

    }
    

    $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
    
    $tempstore->set('num_rows', $tempstore->get('num_rows') + 1);
    $form_state->setRebuild();


  }


  public function validateForm(array &$form, FormStateInterface $form_state) {


  }
  

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
   

    $config = $this->config('hit_page_quotes.settings');
    
    $val = $form_state->getValues();
    $items = [];
  
    foreach($val['items'] as $key => $item) {
      if(!empty($item['hit_page_quotes_item_title']) || !empty($item['hit_page_sub_title']) ||  !empty($item['hit_page_quotes_item_descrition']['value'])) {
        $items[] = ['title' => $item['hit_page_quotes_item_title'], 'sub_title' => $item['hit_page_sub_title'], 'text' =>  $item['hit_page_quotes_item_descrition']];     
      }
    }

    if (!empty($val['hit_page_quotes_title'])) {
      $config->set('hit_page_quotes_title', $val['hit_page_quotes_title']);
    }
    

    foreach($items as $key => $item) {
      
       // Set the submitted configuration setting.
       $config->set('hit_page_quotes_items', count($items));
       $config->set('hit_page_quotes_item_title_'.$key, $item['title']);
       $config->set('hit_page_sub_title_'.$key, $item['sub_title']);
       $config->set('hit_page_quotes_item_descrition_'.$key, $item['text']);
       
       $config->save();

    }
 
   
    \Drupal::messenger()->addMessage('Form has been saved successfully');
  }



}