<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\top_tools_ordering_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\Response;

class top_tools_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'top_tools_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state ) {
    $tempstore = \Drupal::service('tempstore.private')->get('top_tools_ordering');

//     drupal_set_title('Manage ordering of Top tools');
//   $weight = -50;
  // $data = [];
//   $attr = [];
//   $attr_checkbox = FALSE;
//   $args = [];
//   $args = arg();
//   $detstination = implode('/', $args);
  // $form['resource_items']['#tree'] = TRUE;
  $data = $this->get_top_tools();
  $count_data = count($data);

  if (empty($tempstore->get('num_rows_top_tools_ordering')) || $tempstore->get('num_rows_top_tools_ordering') == null) {
    $tempstore->set('num_rows_top_tools_ordering', $count_data);

  }

 $form['resource_items']['#tree'] = TRUE;
  for ($i = 0; $i < $tempstore->get('num_rows_top_tools_ordering'); $i++) {

    // $item_id = isset($data[$i]) ? $data[$i]['id'] : 0;
    // $remove_path = 'hot-topics-remove/'.$item_id;
   
    // $remove_link = 'Remove';
    // $links = '';
    // $links = '<div class="hot-topics-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
    // $title = !empty($data[$i]['title']) ? $data[$i]['title'] : '';

  // dd($data);
  // if (empty($form_state['num_rows'])) {
  //   $form_state['num_rows'] = count($data);
  // }
  // for ($i = 0; $i < $form_state['num_rows']; $i++) {
    $item = isset($data[$i]) ? $data[$i] : NULL;
    $item_id = isset($item) ? $item['id'] : 0;
    $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];

    $form['resource_items'][$i] = [
      'resource' => [
        '#type' => 'textfield',
        '#default_value' => !empty($data[$i]['title']) ? $data[$i]['title'].' [nid:'.$data[$i]['id'].']' : '',
        // '#default_value' => isset($data) ? $data['title'] . ' [nid:' . $data['id'] . ']' : '',
        '#size' => 60,
        '#maxlength' => 255,
        '#required' => FALSE,
        '#attributes' => $attr,
      ],
    
      'weight' => [
        '#type' => 'weight',
        '#title' => t('Weight'),
        // '#default_value' => $weight,
        '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : -0,
        '#delta' => 50,
        '#title_display' => 'invisible',
      ],
      'know_id' => [
        '#type' => 'item',
        '#markup' => !empty($data[$i]['know_id']) ? $data[$i]['know_id'] : '',
        // '#markup' => isset($item) ? $item['know_id'] : '',
      ],
    ];
    $weight++;
  }
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save Changes'),
    // '#submit' => array([$this,'_top_tools_ordering_form_save']),
  );
  $form['actions']['download_all'] = array(
    '#type' => 'submit',
    '#value' => t('Download CSV'),
    '#submit' => array([$this,'_top_tools_ordering_form_download_all']),
  );
  return $form;



  }


  function _top_tools_ordering_form_download_all(&$form, &$form_state) {
   
    $data = [];
    $data =  $this->get_top_tools();
    $row = [];
    $row[] = ['Title', 'KNOW ID'];
    foreach ($data as $val) {
      $row[] = [$val['title'], $val['know_id']];
    }
    $filename = 'Top-tools-' . time() . '.csv';
   

    $this->scf_reports_array_to_csv_download($row, $filename, $delimiter = ",");
  

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



 
    public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state['values']['resource_items'] as $id => $item) {
        $matches = array();
        $result = preg_match('/\[.*\:(\d+)\]$/', $item['resource'], $matches);
        $nid = isset($matches[$result]) ? $matches[$result] : 0;
        if ($nid > 0) {
         \Drupal::database()->merge('scf_top_tools_ordering')
            ->key(array('nid' => $nid))
            ->fields(array(
              'nid' => $nid,
              'weight' => $item['weight'],
              'enabled' => 1,
            ))
            ->execute();
        }
      }
   
      \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');

}

function get_top_tools() {
    $data = [];
    $know_id = '';
    $query = \Drupal::database()->select('scf_top_tools_ordering', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->addField('r', 'nid', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('n', 'title', 'title');
    $query->addField('r', 'enabled', 'enabled');
    $query->condition('n.status', 1);
    $query->orderBy('r.weight', 'ASC');
    $query->orderBy('n.title', 'ASC');
    $result = $query->execute()->fetchAll();
    // dd($result);
    foreach ($result as $key => $item) {
      $know_id = $this->get_know_id_by_nid($item->id);
      $data[] = [
        'id' => is_object($item) ? $item->id : 0,
        'title' => is_object($item) ? $item->title : '',
        'know_id' => $know_id,
        'is_selected' => is_object($item) ? $item->enabled : 0,
        'weight' => is_object($item) ? $item->weight : 50,
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


