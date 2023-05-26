<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\Cdh_page_first_alert_form.
 */
namespace Drupal\cdh_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

class Cdh_page_first_alert_form extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cdh_page_first_alert_form';
  }
  
      /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_first_alert.settings'];
    }

    /**
     * {@inheritdoc}
     */


  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cdh_page_first_alert.settings');

    $tempstore = \Drupal::service('tempstore.private')->get('cdh_page');

    $query = \Drupal::database()->select('cdh_first_alert_orderingg', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
    $query->addField('l', 'field_last_name_value', 'field_last_name_value');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'nid', 'nid');
    $query->addField('r', 'weight', 'weight');
    $query->addField('n', 'title', 'title');
    $query->condition('n.status', 1);
    $query->condition('r.key_contact', 0);
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();
    $data = [];

    if (!empty($result) && $result != null) {
      foreach ($result as $ke => $val) {
        $data[$ke]['field_last_name_value'] = $val->field_last_name_value;
        $data[$ke]['id'] = $val->id;
        $data[$ke]['nid'] = $val->nid;
        $data[$ke]['title'] = $val->title;
        $data[$ke]['weight'] = $val->weight;

      
      }

      $count_data = count($data);
    }

    if (empty($tempstore->get('num_rows_cdh_first_alert_page')) || $tempstore->get('num_rows_cdh_first_alert_page') == null) {
      $tempstore->set('num_rows_cdh_first_alert_page', $count_data);

    }

    $form['cdh_first_alert_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('cdh_first_alert_title'),
      '#weight' => -1,
    );
    
    $form['cdh_first_alert_item']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_cdh_first_alert_page'); $i++) {
      $cdh_first_alert_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      $remove_path = '/admin/cdh-page/cdh-team/first-alert/experts-remove/'.$cdh_first_alert_id;
     
      $remove_link = 'Remove';
      $links = '';
      $links = '<div class="cdh-first-alert-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
      $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
      $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';
     
      $form['cdh_first_alert_item'][$i] = [
        'item' => [
          '#type' => 'textfield',
          '#default_value' => !empty($data[$i]['nid']) ? $fname . ' ' .' '. $lname.' [ '.'nid:'.$data[$i]['nid'].' ] ' : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#autocomplete_route_name' => 'cdh_page.cdh_first_alert_autocomplete',
          '#required' => FALSE,
          '#attributes' => ['class' => ['names_fieldset_input']],
          
        ],
        'first_alert_id' => [
          '#type' => 'hidden',
          '#default_value' => isset($data[$i]) ? $data[$i]['id'] : 0,
          ],
        
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : -0,
          '#delta' => 50,
          '#title_display' => 'visible',
        ],
        'links' => [
          '#type' => 'item',
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
      '#value' => $this->t('Add Expert'),
      '#submit' => ['::cdh_first_alert_form_add_more'],
     
    ];

    return $form;
   
    
  }

  function cdh_first_alert_form_add_more(array &$form, FormStateInterface $form_state)
  { 
   
    $tempstore = \Drupal::service('tempstore.private')->get('cdh_page');
    if (empty($tempstore->get('num_rows_cdh_first_alert_page')) || $tempstore->get('num_rows_cdh_first_alert_page') == null) {
      $tempstore->set('num_rows_cdh_first_alert_page', 1);
    }else{
      $tempstore->set('num_rows_cdh_first_alert_page', $tempstore->get('num_rows_cdh_first_alert_page') + 1);
    }
    $form_state->setRebuild();
  
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
  
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $val = $form_state->getValues();
    $this->config('cdh_page_first_alert.settings')
      ->set('cdh_first_alert_title', $val['cdh_first_alert_title'])->save();
   
    foreach($val['cdh_first_alert_item'] as $key => $item) {
    
      if (!empty($item['item'])) {
        if (preg_match('/nid:\d+/i', $item['item'], $matches, PREG_OFFSET_CAPTURE)) {
          if (!empty($matches[0][0]) && isset($matches[0][0])) {
            if (preg_match('/\d+/i', $matches[0][0], $matche, PREG_OFFSET_CAPTURE)) {
              $nid = $matche[0][0];
            }
          }
        } 
      }
  
     
      if(!empty($item['item']) && !empty($nid)) {
        $items[] = ['item' => $nid,'weight' =>  $item['weight'],'first_alert_id' =>  $item['first_alert_id']];     
      }
    }

    $tempstore = \Drupal::service('tempstore.private')->get('cdh_page');
    if ($items != null) {
      $tempstore->set('num_rows_cdh_first_alert_page', count($items));
    }
  
    // dd($items);
    foreach($items as $key => $item) {
      if($item['first_alert_id'] > 0 ){
        \Drupal::database()->update('cdh_first_alert_orderingg')
       ->fields(array(
        'nid' => $item['item'],
        'key_contact' => 0,
        'weight' => $item['weight'],
      ))
      ->condition('id', $item['first_alert_id'])
      ->execute();
    
      } else {
        $query = \Drupal::database()->insert('cdh_first_alert_orderingg')
        ->fields(array(
          'nid' => $item['item'],
          'key_contact' => 0,
          'weight' => $item['weight'],
        ))->execute();
    
      }
  
    }

    \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');

  }

    



function autocomplete_item_cdh_first_alert()
{
  $string = arg(6);
  $matches = array();
  if ($string) {
    $data = [];
    $query = \Drupal::database()->select('cdh_first_alert_ordering', 'r');
    $query->join('node', 'n', 'r.nid = n.nid');
    $query->addField('r', 'nid', 'nid');
    $query->condition('n.status', 1);
    $query->condition('r.key_contact', 0);
    $result = $query->execute()->fetchAll();
    foreach ($result as $key => $item) {
      $data[] = $item->nid;
    }
    $query = \Drupal::database()->select('node', 'n');
    $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
    $query->leftjoin('field_data_field_expert_search', 'f', 'f.entity_id = n.nid');
    $query->addField('l', 'field_last_name_value', 'field_last_name_value');
    $query->addField('f', 'field_expert_search_value', 'field_expert_search_value');
    $query->addField('n', 'nid', 'nid');
    $query->addField('n', 'title', 'title');
    $query->condition('n.type', ['experts'], 'IN')
      ->condition('n.status', 1)
      ->condition('f.field_expert_search_value', db_like($string) . '%', 'LIKE');
    $items = $query->execute();
    foreach ($items as $item) {
      $fname = is_object($item) ? $item->title : '';
      $lname = is_object($item) ? $item->field_last_name_value : '';
      if (!in_array($item->nid, $data)) {
        $matches[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = check_plain($fname . ' ' . $lname);
      }
    }
  }
  drupal_json_output($matches);
}

}