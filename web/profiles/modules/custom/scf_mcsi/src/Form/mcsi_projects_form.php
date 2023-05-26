<?php
/**
 * @file
 * Contains \Drupal\scf_mcsi\mcsi_projects_form.
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



 

class mcsi_projects_form extends FormBase {


  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mcsi_projects_form';
  }
  /** 
   * {@inheritdoc}
   */

 
  public function buildForm(array $form, FormStateInterface $form_state) {



  $query =\Drupal::database()->select('scf_mcsi_projects_ordering', 'r');
  $query->join('node_field_data', 'n', 'r.nid = n.nid');
  $query->addField('r', 'nid', 'id');
  $query->addField('r', 'weight', 'weight');
  $query->addField('r', 'status', 'status');
  $query->addField('n', 'title', 'title');
  $query->orderBy('r.weight', 'ASC');
  $result = $query->execute()->fetchAll();
  ///node/42086/edit?destination=admin/mcsi/projects
  foreach ($result as $key => $item) {
    $rows[] = [
      // 'id' => is_object($item) ? $item->id : 0,
      'weight' => is_object($item) ? $item->weight : 50,
      'title' => is_object($item) ? $item->title : '',
      // 'status' => is_object($item) ? $item->status : 0,
      'status' => $item->status==1 ? 'Yes' : 'No',
      'edit' => Markup::create('<a href="/node/'.$item->id.'/edit?destination=admin/mcsi/projects">edit</a>'),
      
    ];
  }
  // dd( $data);
  // return $data;


  $header = [
    'weight' => t('Weight'),
    'title'=> t ('Projects'), 
  'status' => t('Published'),
  'edit' => $this->t('Actions')
  ];
    $form['table'] = [
      '#type' => 'table',
      '#header' =>  $header,
      '#rows' => $rows,
  
  ];
  








    // drupal_set_title('MCSI Projects');
    // $args = ar g();
    
//      implode(" ",$arr);
//  $detstination = implode("/", $args);
//   dd($args);
    $data = $this->get_mcsi_projects();
  
    $form['items']['#tree'] = TRUE;
    $form['mcsi_projects_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
    //   '#default_value' => alt_vars_get('mcsi_projects_title'),
    '#default_value' =>'Current studies',
      '#weight' => -1,
    );
    // if (empty($form_state['num_rows'])) {
    //   $form_state['num_rows'] = count($data);
    // }
    // dd($dat);
    // for ($i = 0; $i < $form_state['num_rows']; $i++) {
      // $item = isset($data[$i]) ? $data[$i] : NULL;
      
      // $item_id = isset($item) ? $item['id'] : 0;
    
      // $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];

      // $remove_path = 'node/' . $item_id . '/edit';
      // dd($remove_path);
      // // $remove_link = l('Edit', $remove_path, array('query' => array('destination' => $detstination)));
      // $links = '';
      // $links = '<div class="scf-links">' . $remove_link . '</div>';
  
      // $form['items'][$i] = [
      //   'project' => [
      //     '#type' => 'textfield',
      //     '#default_value' => isset($rows) ? $rows['title'] : '',
      //     '#size' => 60,
      //     '#maxlength' => 255,
      //     '#required' => FALSE,
      //     '#attributes' => $attr,
      //   ],
      //   'status' => [
      //     '#type' => 'item',
      //     '#markup' => isset($rows['status']) && $rows['status'] == 1 ? 'Yes' : 'No',
      //   ],
      //   'links' => [
      //     '#type' => 'item',
      //     '#markup' => $links,
      //   ],
      //   'id' => [
      //     '#type' => 'hidden',
      //     '#default_value' => $rows['id'],
      //   ],
      //   'weight' => [
      //     '#type' => 'weight',
      //     '#title' => t('Weight'),
      //     '#default_value' => $weight,
      //     '#delta' => 50,
      //     '#title_display' => 'invisible',
      //   ],
      // ];
      // $weight++;
    // }
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
      // '#submit' => array('_mcsi_projects_form_save'),
    );
    $form['actions']['add_project'] = array(
      '#type' => 'submit',
      '#value' => t('Add Project'),
      '#submit' => array([$this,'_add_project']),
    );
    return $form;




  }


  function _add_project() {

    // drupal_goto('node/add/mcsi-project', array('query' => array('destination' => 'admin/mcsi/projects')));
    // $redirect =new RedirectResponse('node/add/mcsi-project', array('query' => array('destination' => 'admin/mcsi/projects')));
    $redirect =new RedirectResponse('/node/add/mcsi_project');
    
        $redirect->send();
  }



  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {

  $val= $form_state->getvalues();
  $val = [
    'mcsi_projects_title'=> $form_state->getValue('mcsi_projects_title'),

];
  // $val = $form_state['values'];
  // alt_vars_set('mcsi_projects_title', $val['mcsi_projects_title']);

  foreach ($val['items'] as $id => $item) {
    $nid = isset($item['id']) ? $item['id'] : 0;
    if ($nid > 0) {
      \Drupal::database()->merge('scf_mcsi_projects_ordering')
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









function get_mcsi_projects() {
  $data = [];
  $know_id = '';
  $query =\Drupal::database()->select('scf_mcsi_projects_ordering', 'r');
  $query->join('node_field_data', 'n', 'r.nid = n.nid');
  $query->addField('r', 'nid', 'id');
  $query->addField('r', 'weight', 'weight');
  $query->addField('r', 'status', 'status');
  $query->addField('n', 'title', 'title');
  $query->orderBy('r.weight', 'ASC');
  $result = $query->execute()->fetchAll();
  foreach ($result as $key => $item) {
    $data[] = [
      'id' => is_object($item) ? $item->id : 0,
      'title' => is_object($item) ? $item->title : '',
      'status' => is_object($item) ? $item->status : 0,
      'weight' => is_object($item) ? $item->weight : 50,
    ];
  }
  return $data;
}










}