<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\industry_materials_ordering_form.
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

class industry_materials_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'industry_materials_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state ) {


    $vid = 'industry';
    // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,1,TRUE);
   
    $tag_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  
    foreach ($tag_terms as $tag_term) {
      $industries[$tag_term->tid] = $tag_term->name;
     }

//   $industries =  get_all_terms_by_vocab('industry');

  $selected_industry = ($industry_id > 0) ? $industry_id : key($industries);
  // dd($selected_industry);
  $form['industry'] = [
    '#type' => 'select',
    '#title' => t('Industry'),
    '#options' => $industries,
    '#default_value' => $selected_industry,
    '#weight' => -10,
    '#attributes' => array('onchange' => "form.submit('topics')"),
  ];

  $form['selected_industry'] = [
      '#type' => 'hidden',
      '#value' => $selected_industry,
  ];

    $i=0;
    $weight = -50;
    $data = [];
    $data = $this->scfp_get_ordered_industry_materials($selected_industry);
    // dd($data);
    foreach($data as $item) {
        $form['resource_items']['#tree'] = TRUE;
        $form['resource_items'][$i] = [
          'resource_id' => [
            '#type' => 'hidden',
            '#default_value' => isset($item) ? $item['id'] : 0,
          ],
          'resource' =>[
            '#type' => 'textfield',
            '#default_value' => isset($item) ? $item['title'].' [nid:'.$item['id'].']' : '',
            '#size' => 60,
            '#maxlength' => 255,
            '#required' => FALSE,
            '#attributes' => ['readonly' => 'readonly'],
          ],
          'know_id' =>[
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
        $i++;
        $weight++;
      }

 $form['actions']['redirect'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
    '#submit' => array([$this,'_industry_materials_ordering_form_redirect']),
    // '#prefix' => '<div style="display:none;">',
    // '#suffix' => '</div>',
  );
 if(count($data) > 0) {
    $form['actions']['save'] = array(
         '#type' => 'submit',
         '#value' => t('Save Changes'),
         '#submit' => array([$this,'_industry_materials_ordering_form_save']),
    );
 }
 $form['actions']['download_all'] = array(
  '#type' => 'submit',
  '#value' => t('Download CSV'),
  '#submit' => array([$this,'_industry_materials_ordering_form_download_all']),
 );
 return $form;

  }





  function _industry_materials_ordering_form_download_all(array & $form, FormStateInterface $form_state) {
    $vid = 'industry';
    $industries = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    // dd( $industries);

    // $industries =  get_all_terms_by_vocab('industry');
    $row = [];
    $row[] = ['Resource Title', 'KNOW ID', 'Industry'];



    foreach($industries as $industry_id => $industry) {
      // dd($industry);
      $data = [];
    //   $data = _scfp_get_ordered_industry_materials($industry_id);
      foreach($data as $val) {
        dd($val);
          $row[] = [$val['title'],$val['know_id'], $industry];
      }
    }



    $filename = 'Industry-materials-'.time().'.csv';
    // scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
}






function _industry_materials_ordering_form_redirect(array & $form, FormStateInterface $form_state) {
//   $val = $form_state['values'];

  $val= $form_state->getValues();


  $industry = isset($val['industry']) ? $val['industry'] : 0;

//   $path = 'admin/managing/manage-resource-order/industry-materials/'.$industry;

//   $form_state['redirect'] = $path;

  $redirect = new RedirectResponse('industry-materials/'.$industry);
//   $redirect = new RedirectResponse($industry$industry);
    $redirect->send();


}

function _industry_materials_ordering_form_save(array & $form, FormStateInterface $form_state) {
//   $val = $form_state['values'];
$val= $form_state->getValues();
  $industry = isset($val['selected_industry']) ? $val['selected_industry'] : 0;
  if($industry > 0) {
    foreach ($val['resource_items'] as $id => $item) {
        \Drupal::database()->merge('scf_industry_materials_ordering')
        ->key(array('nid' => $item['resource_id'],
        'industry_id' => $industry
        ))
        ->fields(array(
          'weight' => $item['weight']
        ))
        ->execute();
    }
  }
  
  \Drupal::messenger()->addMessage('Form has been saved successfully');
}




  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {


}






function scfp_get_ordered_industry_materials($industry_id) {
  $data = [];
  $know_id = '';
  if ($industry_id > 0) {
    $query = \Drupal::database()->select('scf_industry_materials_ordering', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->leftjoin('field_data_field_is_secondary_resource', 's', 's.entity_id = n.nid');
    $query->leftjoin('field_data_field_is_q_a', 'qa', 'qa.entity_id = n.nid');
    $query->addField('r', 'nid', 'id');
    $query->addField('r', 'industry_id', 'industry_id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('n', 'title', 'title');
    $query->addField('qa', 'field_is_q_a_value', 'field_is_q_a_value');
    $query->condition('n.status', 1);
    $query->condition('s.field_is_secondary_resource_value', 0);
    $query->condition('r.industry_id', $industry_id);
    $query->orderBy('r.weight', 'ASC');
    $query->orderBy('n.created', 'ASC');
    // dd($query);
    $result = $query->execute()->fetchAll();
    // dd($result);
    foreach ($result as $key => $item) {
      if (empty($item->field_is_q_a_value) || $item->field_is_q_a_value == 0) {
        $know_id = get_know_id_by_nid($item->id);
        $data[] = [
          'id' => is_object($item) ? $item->id : 0,
          'title' => is_object($item) ? $item->title : '',
          'industry_id' => is_object($item) ? $item->industry_id : 0,
          'weight' => is_object($item) ? $item->weight : 50,
          'know_id' => $know_id,
        ];
      }
    }
  }
  return $data;
}



















}