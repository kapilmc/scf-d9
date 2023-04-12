<?php
/**
 * @file
 * Contains \Drupal\scf_hit_page\Form\HitPageLeadershipForm.
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
class hit_page_leadership_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hit_page_leadership_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
  
    $query = \Drupal::database()->select('scf_hit_page_leadership_ordering', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->addField('r', 'nid', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('r', 'status', 'status');
    $query->addField('n', 'title', 'title');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();
// dd($result);
    foreach ($result as $key => $item) {
      $data[] = [
        'id' => is_object($item) ? $item->id : '',
        'title' => is_object($item) ? $item->title : '',
        'status' => is_object($item) ? $item->status : 0,
        'weight' => is_object($item) ? $item->weight : 50,
      ];
    }
 
    $form['items']['#tree'] = TRUE;

    if($form_state->getValue('num_rows') == NULL){
      // dd($data);
      if ($data != NULL) {
        $form_state->setValue('num_rows', count($data));
      }
      

    }

  

    for ($i = 0; $i < $form_state->getValue('num_rows'); $i++) {
      
      $item = isset($data[$i]) ? $data[$i] : NULL;
      $item_id = isset($item) ? $item['id'] : 0;
      $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];
      $remove_link = 'edit';
      $links = '';
      $links = '<div class="scf-links"><a href="/node/'.$item_id.'/edit?destination=admin/hit-page/leadership-videos">' . $remove_link . '</a></div>';


      $form['items'][$i] = [
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          '#default_value' => isset($item['weight']) ? $item['weight'] : '',
          '#delta' => 50,
          '#title_display' => 'invisible',
        ],
        'project' => [
          '#type' => 'textfield',
          '#default_value' => isset($item) ? $item['title'] : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#required' => FALSE,
          '#attributes' => $attr,
        ],
        'status' => [
          '#type' => 'item',
          '#markup' => isset($item['status']) && $item['status'] == 1 ? 'Yes' : 'No',
        ],
        'links' => [
          '#type' => 'item',
          '#markup' => $links,
        ],
        'id' => [
          '#type' => 'hidden',
          '#default_value' => $item['id'],
        ],
        
      ];

    }


   
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save Changes'),
    // '#submit' => array('_hit_leadership_form_save'),
  );
  $form['actions']['add_project'] = array(
    '#type' => 'submit',
    '#submit'=>array([$this, 'Add_Leadership']),
    '#value' => t('Add Leadership Video'),
    // '#submit' => array('_add_leadership_video'),
  );

  //  dd($form);
  return $form;
   
  }




  
public function Add_Leadership(array &$form, FormStateInterface $form_state) {
  $redirect =new RedirectResponse('/node/add/hit_video?destination=admin/hit-page/leadership-videos');
  $redirect->send();
}



  public function validateForm(array &$form, FormStateInterface $form_state) {

}

 
  public function submitForm(array &$form, FormStateInterface $form_state) {

    
    $data = $form_state->getValues();
    

  //   $data = [
  //     'hit_page_leadership_title'=> $form_state->getValue('hit_page_leadership_title'),
   

  // ];

    foreach ($data['items'] as $id => $item) {
      // dd($item['weight']);
      $nid = isset($item['id']) ? $item['id'] : 0;
      if (!empty($nid)) {
        \Drupal::database()->merge('scf_hit_page_leadership_ordering')
          ->key(array('nid' => $nid))
          ->fields(array(
            'nid' => $nid,
            'weight' => $item['weight'],
          ))
          ->execute();
  
          }

        }

        \Drupal::messenger()->addMessage('Form has been saved successfully');
  
  }




}