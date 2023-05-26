<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\hot_topics_ordering_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

class hot_topics_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hot_topics_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $tempstore = \Drupal::service('tempstore.private')->get('hot_topics');

    // drupal_set_title('Manage ordering of hot topics');
    // $weight = -50;
    // $data = [];
    // $attr = [];
    // $attr_checkbox = FALSE;
    // $args = [];
  	// $args = arg();
  	// $detstination = implode('/',$args);
    // $form['resource_items']['#tree'] = TRUE;




    $data = $this->get_hot_topics();
//  dd($data);

    $count_data = count($data);
  // dd($count_data);

  if (empty($tempstore->get('num_rows_hot_topics')) || $tempstore->get('num_rows_hot_topics') == null) {
    $tempstore->set('num_rows_hot_topics', $count_data);

  }

  $form['#tree'] = TRUE;
  for ($i = 0; $i < $tempstore->get('num_rows_hot_topics'); $i++) {
    $item_id = isset($data[$i]) ? $data[$i]['id'] : 0;
    $remove_path = 'hot-topics-remove/'.$item_id;
   
    $remove_link = 'Remove';
    $links = '';
    $links = '<div class="hot-topics-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
    $title = !empty($data[$i]['title']) ? $data[$i]['title'] : '';

    // dd($title);
    // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';

   
          // for ($i = 0; $i < $num_names; $i++) {

      $form['resource_items'][$i] = [
        'is_selected' => [
          '#type' => 'checkbox',
          '#title' => '&nbsp;',
          '#default_value' => isset($data) ? $data[$i]['is_selected'] : 1,
          '#disabled' => $attr_checkbox,
        ],
        'resource' =>[
          '#type' => 'textfield',
          '#default_value' => !empty($data[$i]['title']) ? $data[$i]['title'].' [nid:'.$data[$i]['id'].']' : '',
          // '#default_value' => !empty($data[$i]['nid']) ? $title . ' ' .' [ '.'nid:'.$data[$i]['nid'].' ] ' : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#autocomplete_route_name' => 'manageordering.hot_topics_ordering_form_autocomplete',
          '#required' => FALSE,
          '#attributes' => $attr,
        ],
        'know_id' =>[
          '#type' => 'item',
          // '#markup' => isset($data) ? $data['know_id'] : '',
          '#markup' => !empty($data[$i]['know_id']) ? $data[$i]['know_id'] : '',
        ],

      
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          // '#default_value' => $weight,
          '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : -0,
          '#delta' => 50,
          '#title_display' => 'invisible',
        ],
        'links' => [
          '#type' => 'item',
          '#markup' => $links,
        ],

      ];
      $weight++;
      }
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
     '#type' => 'submit',
     '#value' => t('Save Changes'),
    //  '#submit' => array('_hot_topics_ordering_form_save'),
   );


  
   $form['actions']['add_name'] = [
    '#type' => 'submit',
    '#value' => $this->t('Add another document'),
    '#submit' => ['::hot_topics_ordering_form_add_more'],
   
  ];


   $form['actions']['download_all'] = array(
      '#type' => 'submit',
      '#value' => t('Download CSV'),
    //   '#submit' => array('_hot_topics_ordering_form_download_all'),
      '#submit'=>array([$this, '_hot_topics_ordering_form_download_all']),
    );
  return $form;

  }


  function _hot_topics_ordering_form_download_all(&$form, &$form_state) {
    $data = [];
    // dd( $data );
    $data = $this->get_hot_topics();
    $row = [];
    $row[] = ['Title', 'KNOW ID', 'Enabled'];
    foreach($data as $val) {
        $row[] = [$val['title'], $val['know_id'], ($val['is_selected'] == 1) ? 'Yes': 'No'];
    }
    $filename = 'Hot-topics-'.time().'.csv';
    // dd($filename);
    $this->scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
  }



  function scf_reports_array_to_csv_download($rows, $filename = "export.csv", $delimiter=",") {
    // $filename = scf_clean_filename(strip_tags(strtolower($filename)));
     header('Content-Type: application/csv');
     header('Content-Disposition: attachment; filename="'.$filename.'";');
     $f = fopen('php://output', 'w');
     $func = function($value) {
       return ltrim($value, "-");
     };
     foreach ($rows as $line) {
       $line = array_map($func, $line);
       fputcsv($f, $line, $delimiter);
     }
     exit();
  }









    public function hot_topics_ordering_form_add_more (array &$form, FormStateInterface $form_state) {


      $tempstore = \Drupal::service('tempstore.private')->get('hot_topics');
      if (empty($tempstore->get('num_rows_hot_topics')) || $tempstore->get('num_rows_hot_topics') == null) {
        $tempstore->set('num_rows_hot_topics', 1);
      }else{
        $tempstore->set('num_rows_hot_topics', $tempstore->get('num_rows_hot_topics') + 1);
      }
      $form_state->setRebuild();




        }
  
  



  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {


    foreach ($form_state->getValue(['resource_items']) as $id => $item) {
        
    // foreach ($form_state['values']['resource_items'] as $id => $item) {
    	$matches = array();
    	$result = preg_match('/\[.*\:(\d+)\]$/', $item['resource'], $matches);

    	$nid = isset($matches[$result]) ? $matches[$result] : 0;
        // dd($nid);
    	if($nid > 0) {
  	    // db_merge('scf_hot_topics_ordering')
       
          \Drupal::database()->merge('scf_hot_topics_ordering')
        ->insertFields(array(
      
          'nid' => $nid,
          'weight' => $item['weight'],
          'enabled' => $item['is_selected'],
          'created' => 0,
        //   'created' => time(),
        ))
        // dd( $query);
        ->updateFields(array(
            // \Drupal::database()->update(array(
          'nid' => $nid,
          'weight' => $item['weight'],
          'enabled' => $item['is_selected']
        ))
  	  	->key(array('nid' => $nid))
            // ->key(array('nid' =>$nid['resource_id']))
  	  	->execute();
  	  }
    }


    \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');
  

}









function get_hot_topics() {
    $data = [];
    $know_id = '';
    $query = \Drupal::database()->select('scf_hot_topics_ordering', 'r');
    // dd($query);
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->addField('r', 'nid', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('n', 'title', 'title');
    $query->addField('r', 'enabled', 'enabled');
    $query->condition('n.status', 1);
    $query->orderBy('r.weight', 'ASC');
    $query->orderBy('n.created', 'ASC');
    $result = $query->execute()->fetchAll();
    // dd($result);
    foreach ($result as $key => $item) {
      $know_id = $this->get_know_id_by_nid($item->id);
      // dd($know_id);
      $data[] = ['id' => is_object($item) ? $item->id : 0,
    						 'title' => is_object($item) ? $item->title : '',
                 'is_selected' => is_object($item) ? $item->enabled : 0,
                 'weight' => is_object($item) ? $item->weight : 50,
                 'know_id' => $know_id,
    						];
   }
   return $data;
}



function get_know_id_by_nid($nid = 0) {
  $query = \Drupal::database()->select('node_field_data', 'n');
  $query->leftjoin('field_data_field_know_id', 'kw', 'kw.entity_id = n.nid');
  // $query->leftjoin('field_data_field_is_know_document', 'ikw', 'ikw.entity_id = n.nid');
  $query->addField('kw', 'field_know_id_value', 'know_id');
  // $query->condition('ikw.field_is_know_document_value', 1);
  $query->condition('n.nid', $nid);
  // dd($query);
  return $result = $query->execute()->fetchField();
}





}