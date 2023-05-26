<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\\Form\hit_page_key_contact_form.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\HttpFoundation\Request;

class hit_page_key_contact_form extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_key_contact_form';
  }
  protected function getEditableConfigNames()
  {
      return ['hit_page_key_contact.settings'];
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('hit_page_key_contact.settings');

    $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');

    $query = \Drupal::database()->select('scf_hit_team_ordering', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
    $query->addField('l', 'field_last_name_value', 'field_last_name_value');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'nid', 'nid');
    $query->addField('r', 'weight', 'weight');
    $query->addField('n', 'title', 'title');
    $query->condition('n.status', 1);
    $query->condition('r.key_contact', 1);
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

    if (empty($tempstore->get('num_rows_key_contact_page')) || $tempstore->get('num_rows_key_contact_page') == null) {
      $tempstore->set('num_rows_key_contact_page', $count_data);

    }
    $form['hit_key_contact_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' =>  $config->get('hit_key_contact_title'),
      '#weight' => -1,
    );
    $form['key_contact_items']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_key_contact_page'); $i++) {
      $key_contact_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      $remove_path = '/admin/hit-page/key-contact/experts-remove/'.$key_contact_id;
     
      $remove_link = 'Remove';
      $links = '';
      $links = '<div class=key-contact-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
      $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
      $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';
     
      $form['key_contact_items'][$i] = [
        'item' => [
          '#type' => 'textfield',
          '#default_value' => !empty($data[$i]['nid']) ? $fname . ' ' .' '. $lname.' [ '.'nid:'.$data[$i]['nid'].' ] ' : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#autocomplete_route_name' => 'scf_hit_page.key_autocomplete',
          '#required' => FALSE,
          '#attributes' => ['class' => ['names_fieldset_input']],
          
        ],
        'team_id' => [
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
      '#submit' => ['::home_key_contact_form_add_more'],
     
    ];

 return $form;

}


function home_key_contact_form_add_more(array &$form, FormStateInterface $form_state)
{ 
 
  $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
  if (empty($tempstore->get('num_rows_key_contact_page')) || $tempstore->get('num_rows_key_contact_page') == null) {
    $tempstore->set('num_rows_key_contact_page', 1);
  }else{
    $tempstore->set('num_rows_key_contact_page', $tempstore->get('num_rows_key_contact_page') + 1);
  }
  $form_state->setRebuild();


}



  public function validateForm(array &$form, FormStateInterface $form_state) {

  }



  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $val = $form_state->getValues();
   
    $this->config('hit_page_key_contact.settings')
      ->set('hit_key_contact_title', $val['hit_key_contact_title'])->save();
      // dd($val);
    foreach($val['key_contact_items'] as $key => $item) {
    
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
        $items[] = ['item' => $nid,'weight' =>  $item['weight'],'team_id' =>  $item['team_id']];     
      }
    }
    $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
    if ($items != null) {
      $tempstore->set('num_rows_key_contact_page', count($items));
    }
    foreach($items as $key => $item) {
      if($item['team_id'] > 0 ){
        \Drupal::database()->update('scf_hit_team_ordering')
      ->fields(array(
        'nid' => $item['item'],
        'key_contact' => 1,
        'weight' => $item['weight'],
      ))
      ->condition('id', $item['news_id'])
      ->execute();
    
      } else {
        $query = \Drupal::database()->insert('scf_hit_team_ordering')
        ->fields(array(
          'nid' => $item['item'],
          'key_contact' => 1,
          'weight' => $item['weight'],
        ))->execute();
    
      }

    }
    \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');


  
  }

}







