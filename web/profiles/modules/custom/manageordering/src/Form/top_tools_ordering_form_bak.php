<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\top_tools_ordering_form_bak.
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

class top_tools_ordering_form_bak extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'top_tools_ordering_form_bak';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state ) {
    drupal_set_title('Manage ordering of Top tools');
$weight = -50;
$data = [];
$attr = [];
$attr_checkbox = FALSE;
$args = [];
$args = arg();
$detstination = implode('/', $args);
$form['resource_items']['#tree'] = TRUE;
$data = get_top_tools();
if (empty($form_state['num_rows'])) {
  $form_state['num_rows'] = count($data);
}
for ($i = 0; $i < $form_state['num_rows']; $i++) {
  $item = isset($data[$i]) ? $data[$i] : NULL;
  $item_id = isset($item) ? $item['id'] : 0;
  $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];
  $form['resource_items'][$i] = [
    'resource' => [
      '#type' => 'textfield',
      '#default_value' => isset($item) ? $item['title'] . ' [nid:' . $item['id'] . ']' : '',
      '#size' => 60,
      '#maxlength' => 255,
      '#required' => FALSE,
      '#attributes' => $attr,
    ],
    'know_id' => [
      '#type' => 'item',
      '#markup' => isset($item) ? $item['know_id'] : '',
    ],
    'weight' => [
      '#type' => 'weight',
      '#title' => t('Weight'),
      '#default_value' => $weight,
      '#delta' => 50,
      '#title_display' => 'invisible',
    ],
  ];
  $weight++;
}
$form['actions'] = array('#type' => 'actions');
$form['actions']['save'] = array(
  '#type' => 'submit',
  '#value' => t('Save Changes'),
  '#submit' => array('_top_tools_ordering_form_save'),
);
$form['actions']['download_all'] = array(
  '#type' => 'submit',
  '#value' => t('Download CSV'),
  '#submit' => array('_top_tools_ordering_form_download_all'),
);
return $form;

  }



 
  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {


    
}





function _top_tools_ordering_form_download_all(&$form, &$form_state) {
$data = [];
$data = get_top_tools();
$row = [];
$row[] = ['Title', 'KNOW ID'];
foreach ($data as $val) {
  $row[] = [$val['title'], $val['know_id']];
}
$filename = 'Top-tools-' . time() . '.csv';
scf_reports_array_to_csv_download($row, $filename, $delimiter = ",");
}

/**
*
*/
function _top_tools_ordering_form_add_more($form, &$form_state) {
$form_state['num_rows']++;
$form_state['rebuild'] = TRUE;
}

/**
*
*/
function _top_tools_ordering_form_save(&$form, &$form_state) {
foreach ($form_state['values']['resource_items'] as $id => $item) {
  $matches = array();
  $result = preg_match('/\[.*\:(\d+)\]$/', $item['resource'], $matches);
  $nid = isset($matches[$result]) ? $matches[$result] : 0;
  if ($nid > 0) {
    db_merge('scf_top_tools_ordering')
      ->key(array('nid' => $nid))
      ->fields(array(
        'nid' => $nid,
        'weight' => $item['weight'],
        'enabled' => 1,
      ))
      ->execute();
  }
}
drupal_set_message(t('Changes have been saved successfully.'), $type = 'status');
}

/**
* Theme callback for the top_tools_ordering_form form.
*/
function theme_top_tools_ordering_form($variables) {
$form = $variables['form'];
$rows = array();
foreach (element_children($form['resource_items']) as $id) {
  $form['resource_items'][$id]['weight']['#attributes']['class'] = array('resource-item-weight');
  $rows[] = array(
    'data' => array(
      drupal_render($form['resource_items'][$id]['resource']),
      drupal_render($form['resource_items'][$id]['know_id']),
      drupal_render($form['resource_items'][$id]['weight']),
    ),
    'class' => array('draggable'),
  );
}
$header = array(t('Content'), t('KNOW ID'), t('Weight'));
$table_id = 'resource-items-table';
$output = theme('table', array(
  'header' => $header,
  'rows' => $rows,
  'attributes' => array('id' => $table_id),
  'sticky' => FALSE,
));
$output .= drupal_render_children($form);
drupal_add_tabledrag($table_id, 'order', 'sibling', 'resource-item-weight');
return $output;
}

/**
* Get_hot_topics description.
*
* @return array
*/
function get_top_tools() {
$data = [];
$know_id = '';
$query = db_select('scf_top_tools_ordering', 'r');
$query->join('node', 'n', 'r.nid = n.nid');
$query->addField('r', 'nid', 'id');
$query->addField('r', 'weight', 'weight');
$query->addField('n', 'title', 'title');
$query->addField('r', 'enabled', 'enabled');
$query->condition('n.status', 1);
$query->orderBy('r.weight', 'ASC');
$query->orderBy('n.title', 'ASC');
$result = $query->execute()->fetchAll();
foreach ($result as $key => $item) {
  $know_id = get_know_id_by_nid($item->id);
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

/**
* Remove_item_from_hot_topics.
*
* @param int $nid
*   node id.
*/
function remove_item_from_top_tools($nid = 0) {
db_delete('scf_top_tools_ordering')
  ->condition('nid', $nid)
  ->execute();
drupal_goto(drupal_get_destination());
}

/**
* Autocomplete_item_from_hot_topics.
*
* @return JSON
*/
function autocomplete_item_from_top_tools() {
$navigate_user_jouney_uuid = '7fe01294-357f-4f72-86c5-80293fef361e';
$navigate_user_journey_tid = scf_get_tid_from_uuid($navigate_user_jouney_uuid);

$string = arg(5);
$matches = array();
if ($string) {
  $data = [];
  $query = db_select('scf_top_tools_ordering', 'r');
  $query->join('node', 'n', 'r.nid = n.nid');

  $query->addField('r', 'nid', 'id');
  $result = $query->execute()->fetchAll();
  foreach ($result as $key => $item) {
    $data[] = $item->id;
  }
  $query = db_select('node', 'n')
    ->fields('n', ['nid', 'title']);
  $query->join('resource_content_order', 'c', 'n.nid = c.nid');
  $query->condition('n.type', ['resource'], 'IN')
    ->condition('n.status', 1)
    ->condition('c.user_journey', $navigate_user_journey_tid)
    ->condition('title', db_like($string) . '%', 'LIKE');
  $items = $query->execute();
  foreach ($items as $item) {
    if (!in_array($item->nid, $data)) {
      $matches[$item->title . ' [nid:' . $item->nid . ']'] = check_plain($item->title);
    }
  }
}
drupal_json_output($matches);
}
}