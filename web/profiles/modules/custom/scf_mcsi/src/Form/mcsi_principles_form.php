<?php
/**
 * @file
 * Contains \Drupal\scf_mcsi\mcsi_principles_form.
 */
namespace Drupal\scf_mcsi\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;




 

class mcsi_principles_form extends FormBase {


  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mcsi_principles_form';
  }
  /** 
   * {@inheritdoc}
   */

 
  public function buildForm(array $form, FormStateInterface $form_state) {

    // $options = array(
    //   'query'      => ['type' => 'overview form', 'status' => 1],
    //   'fragment'   => 'form-list',
    //   'attributes' => ['class' => ['btn', 'btn-mini']],
    //   'absolute'   => TRUE,
    // );
    // $link_overview = Link::fromTextAndUrl(t('Overview'), Url::fromUri('internal:/admin/mcsi/overview', $options))->toString();
    // $options = array(
    //   'query'      => ['type' => 'Principles form', 'status' => 1],
    //   'fragment'   => 'Principles-list',
    //   'attributes' => ['class' => ['btn', 'btn-mini']],
    //   'absolute'   => TRUE,
    // );
    // $link_principles = Link::fromTextAndUrl(t('Principles'), Url::fromUri('internal:/admin/mcsi/overview/principles', $options))->toString();
    

    // $form['overview_child_tab'] = [
    //   '#markup' => Markup::create('<span class="overview_child_tab">'.$link_overview.'</span>'),
    // ];
    // $form['principal_tab'] = [
    //   '#markup' => Markup::create('<span class="principles_child_tab">'.$link_principles.'</span>'),
    // ];

  $query = \Drupal::database()->select('scf_mcsi_principles_ordering', 'r');
  $query->join('node_field_data', 'n', 'r.nid = n.nid');
  $query->addField('r', 'nid', 'id');
  $query->addField('r', 'weight', 'weight');
  $query->addField('r', 'status', 'status');
  $query->addField('n', 'title', 'title');
  $query->orderBy('r.weight', 'ASC');
  // dd($query);
  $result = $query->execute()->fetchAll();
// dd($result );

$rows = [];
  foreach ($result as $key => $item) {
    // dd($item->id);
      
    //  $url_edit = Url::fromRoute('demo.demo', ['nid' => $item->nid],[]);

      // $linkEdit = Link::fromTextAndUrl('edit', $url_edit);
      //node/42093/edit?destination=admin/mcsi/overview/principles
    $rows[] = [

      // 'id' => is_object($item) ? $item->id : 0,
      'weight' => is_object($item) ? $item->weight : 50,
      'title' => is_object($item) ? $item->title : '',
      'status' => $item->status==1 ? 'Yes' : 'No',
      
      'edit' => Markup::create('<a href="/node/'.$item->id.'/edit?destination=admin/mcsi/overview/principles">edit</a>'),
      // 'edit'=>$item->$linkEdit,
    ];
  }

// dd( $data);


$header = [
  'weight' => t('Weight'),
  'title'=> t ('Principles'), 
'status' => t('Published'),


'edit' => $this->t('Actions')

];

  $form['table'] = [
    '#type' => 'table',
    '#header' =>  $header,
    '#rows' => $rows,

    '#empty' => $this->t('No data found'),
];




$conn = Database::getConnection();
$data = [];
if (isset($_GET['nid'])) {
    $query = $conn->select('scf_mcsi_principles_ordering', 'm')
        ->condition('nid', $_GET['nid'])
        ->fields('m');
    $data = $query->execute()->fetchAssoc();

}


$form['items']['#tree'] = TRUE;
$form['mcsi_principles_title'] = array(
  '#type' => 'textfield',
  '#title' => t('Title'),
  // '#default_value' => alt_vars_get('mcsi_principles_title'),

  '#default_value'=>'Our principles ',
  '#weight' => -1,
);

  // if (empty($form_state['num_rows'])) {
  //   $form_state['num_rows'] = count($data);
  // }
  // for ($i = 0; $i < $form_state['num_rows']; $i++) {
  //   $item = isset($data[$i]) ? $data[$i] : NULL;
  //   $item_id = isset($item) ? $item['id'] : 0;
  //   $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];
  //   $remove_path = 'node/' . $item_id . '/edit';
  //   $remove_link = l('Edit', $remove_path, array('query' => array('destination' => $detstination)));
  //   $links = '';
  //   $links = '<div class="scf-links">' . $remove_link . '</div>';


    $form['items'][$i] = [
      // 'principles' => [
      //   '#type' => 'textfield',
      //   // '#default_value' => isset($item) ? $item['title'] : '',
      //   '#size' => 60,
      //   '#maxlength' => 255,
      //   '#required' => FALSE,
      //   '#attributes' => $attr,
      // ],
      'status' => [
        '#type' => 'item',
        // '#markup' => isset($item['status']) && $item['status'] == 1 ? 'Yes' : 'No',
      ],
      'links' => [
        '#type' => 'item',
        '#markup' => $links,
      ],
      'id' => [
        '#type' => 'hidden',
        // '#default_value' => $item['id'],
      ],
      // 'weight' => [
      //   '#type' => 'weight',
      //   '#title' => t('Weight'),
      //   '#default_value' => $weight,
      //   '#delta' => 50,
      //   '#title_display' => 'invisible',
      // ],
    ];
    $weight++;
  // }
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save Changes'),
    // '#submit' => array('_mcsi_principles_form_save'),
  );
  $form['actions']['add_principles'] = array(
    '#type' => 'submit',
    '#value' => t('Add Principles'),
    '#submit' => array([$this,'_add_principles']),
  );






  return $form;






  }

  function _add_principles() {

  // drupal_goto('node/add/principles', array('query' => array('destination' => 'admin/mcsi/overview/principles')));

    $redirect =new RedirectResponse('/node/add/principles');



      // $redirect =new RedirectResponse('/node/add/principles', array('query' => array('destination' => 'admin/mcsi/overview/principles')));
    
    $redirect->send();
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {

  $val= $form_state->getvalues();
      // dd($val);

      $val = [
        'mcsi_principles_title'=> $form_state->getValue('mcsi_principles_title'),
    
    ];
    
  foreach ($val['items'] as $id => $item) {
    $nid = isset($item['id']) ? $item['id'] : 0;
    if ($nid > 0) {
      \Drupal::database()->merge('scf_mcsi_principles_ordering')
        ->key(array('nid' => $nid))
        ->fields(array(
          'nid' => $nid,
          'weight' => $item['weight'],
        ))
        ->execute();
    }
  }
 \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');


}



}