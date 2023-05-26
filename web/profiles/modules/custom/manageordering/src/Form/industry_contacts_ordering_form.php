<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\industry_contacts_ordering_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

class industry_contacts_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'industry_contacts_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {

    $vid = 'industry';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,1,TRUE);
    // dd( $industries);
   


    foreach ($terms as $term) {
        
        // dd($term);
        $industries[]= array(
            
        'name'=> $term->name->value,
        );
    }
    //   dd( $data);

    // drupal_set_title('Manage ordering of S&CF industry contacts');
    // $industries =  get_all_terms_by_vocab('industry');
    $selected_industry = ($industry_id > 0) ? $industry_id : key($industries);
    $form['industry'] = [
      '#type' => 'select',
      '#title' => t('Industry'),
      '#options' => $industries,
      '#default_value' => $selected_industry,
      '#weight' => -10,
    //   '#attributes' => array('onchange' => "form.submit('topics')"),
    ];
  
    $form['selected_industry'] = [
        '#type' => 'hidden',
        '#value' => $selected_industry,
    ];
  
    //   $i=0;
    //   $weight = -50;
    //   $data = [];
    //   $data = _scfp_get_ordered_industry_contacts($selected_industry);

    //   foreach($data as $item) {
        //   $form['resource_items']['#tree'] = TRUE;
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
            'role' =>[
              '#type' => 'item',
              '#markup' => isset($item) ? $item['role'] : '',
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
        // }
  
//    $form['actions']['redirect'] = array(
//       '#type' => 'submit',
//       '#value' => t('Submit'),
//       '#submit' => array($this,'_industry_contacts_ordering_form_redirect'),
//       '#prefix' => '<div style="display:none;">',
//       '#suffix' => '</div>',
//     );
//    if(count($data) > 0) {
      $form['actions']['save'] = array(
           '#type' => 'submit',
           '#value' => t('Save Changes'),
        //    '#submit' => array($this,'_industry_contacts_ordering_form_save'),
      );
//    }
   $form['actions']['download_all'] = array(
    '#type' => 'submit',
    '#value' => t('Download CSV'),
    '#submit' => array($this,'_industry_contacts_ordering_form_download_all'),
   );
   return $form;




  }



  function _industry_contacts_ordering_form_download_all(&$form, &$form_state) {
    $industries =  get_all_terms_by_vocab('industry');
    $row = [];
    $row[] = ['Resource Title', 'Role', 'Industry'];
    foreach($industries as $industry_id => $industry) {
      $data = [];
      $data = _scfp_get_ordered_industry_contacts($industry_id);
      foreach($data as $val) {
          $row[] = [$val['title'],$val['role'], $industry];
      }
    }
    $filename = 'Industry-contacts-'.time().'.csv';
    scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
}




  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {
    $val= $form_state->getValues();
    // dd($val);
    // $val = $form_state['values'];
  $industry = isset($val['selected_industry']) ? $val['selected_industry'] : 0;
  if($industry > 0) {
    foreach ($val['resource_items'] as $id => $item) {
        // dd($item);
        \Drupal::database()->merge('scf_industry_contacts_ordering')
        ->key(array('nid' => $item['resource_id'],
        'industry_id' => $industry
        ))
        ->fields(array(
          'weight' => $item['weight']
        ))
        ->execute();
    }
  }
//   dd($industry);
  \Drupal::messenger()->addMessage('Form has been saved successfully');
}













function _industry_contacts_ordering_form_redirect(&$form, &$form_state) {
  $val = $form_state['values'];
  $industry = isset($val['industry']) ? $val['industry'] : 0;
  $path = 'admin/managing/manage-first-alert-and-expert-order/industry-contacts/'.$industry;
  $form_state['redirect'] = $path;
}




}