<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\ContinuosLearningOrderingForm.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

class continuous_learning_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'continuos_learning_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $vid =  'continuous_learning';



   $tag_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  // $options = $options = [] +[];
  foreach ($tag_terms as $tag_term) {
   $options[$tag_term->tid] = $tag_term->name;
   }




    // $options = [];
    //   foreach ($terms as $t => $v) {
    //     if($t!=0){
    //     $options[$v->tid->value]= $v->name->value;
    //   }
    // }
   
    $title = 'S&CF Learning Programs';
    $weight = -50;
    $journey_tid = ('7fe01294-357f-4f72-86c5-80293fef361e');
    $journey_tid = '7fe01294-357f-4f72-86c5-80293fef361e';
    $cat = 0;
    $seven = 0;
    $type = 'get_involved';
  
    $i=0;
    $weight = -50;
  
  
    if($topic_tid == 0) {
      $topic_tid = key($options);
    }
    $form['topic'] = [
      '#type' => 'select',
      '#title' => $title,
      '#options' => $options,
      '#default_value' => $topic_tid,
      '#weight' => -10,
      '#attributes' => array('onchange' => "form.submit('topics')"),
    ];
    $form['user_journey'] = [
      '#type' => 'hidden',
      '#value' => (int)$journey_tid,
    ];
    $data = [];
   
    // $data = _scfp_get_ordered_resource($journey_tid, $topic_tid, $cat, $seven, $type);
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
  
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['redirect'] = array(
       '#type' => 'submit',
       '#value' => t('Submit'),
    //    '#submit' => array('_continuous_learning_ordering_form_redirect'),
    '#submit' => array([$this,'_continuous_learning_ordering_form_redirect']),
       '#prefix' => '<div style="display:none;">',
       '#suffix' => '</div>',
     );
    $form['actions']['save'] = array(
       '#type' => 'submit',
       '#value' => t('Save Changes'),
    //    '#submit' => array('_continuous_learning_ordering_form_save'),
    // '#submit' => array([$this,'_continuous_learning_ordering_form_save']),
     );
  
     $form['actions']['download_all'] = array(  
        '#type' => 'submit',
        '#value' => t('Download CSV'),
        // '#submit' => array('_continuous_learning_ordering_form_download_all'),
         '#submit' => array([$this,'_continuous_learning_ordering_form_download_all']),
      );













 $header = array(t('Resources'), t('KNOW ID'), t('Weight'));
      $form['table'] = [
        '#type' => 'table',
      
        '#header' => $header,
        // '#rows' => $rows,
        // '#empty' => t('No content has been found.'),
      ];

              return $form;
      
      
      // $help_text = '';
      // $header = array(t('Resources'), t('KNOW ID'), t('Weight'));
      // // $table_id = 'resource-items-table';
      // // $output = drupal_render($form['topic']);
      // // $output .= drupal_render($form['seven_steps']);
      // // $output .= drupal_render($form['resource_category']);
      // // $output .= theme('table', array(
      // //   'header' => $header,
      // //   //'caption' => $help_text,
      // //   'rows' => $rows,
      // //   'attributes' => array('id' => $table_id),
      // //   'sticky' => FALSE,
      // // ));






      
  


      
      
            

  }



  function _continuous_learning_ordering_form_download_all(array &$form, FormStateInterface $form_state) {
    // $val = $form_state['values'];
   $val= $form_state->getValues();
   $vid =  'continuous_learning';





   $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,3,TRUE);
// dd($terms);
   foreach ($terms as $term) {
       // dd($term);
       $options[]= array(
           
       'id'=> $term->tid->value,
      //  'name'=> $term->name->value,
       );
   }
// dd($options);

    // $user_journey = isset($val['user_journey']) ? (int)$val['user_journey'] : 0;
    
    // $topic = 0;
    // $type = 'get_involved';
    // $navigate = 0;
    // $cat = 0;
    // $win = 0;
    // $inspire = 0;
    // $seven = 0;
    // $row = [];
    // $row[] = ['S&CF Learning Programs', 'Title', 'KNOW ID'];
    // // $options =  scf_get_options('continuous_learning');

    // $options=
    foreach($options as $t => $v) {
     
      foreach($v as $tt => $vv) {
        dd($vv);
        if(!is_int($tt)) {
          continue;
        }
        $topic_name = '';
        $topic_name =  strip_tags($vv);

        dd($topic_name);

        $data = [];
        $data = _scfp_get_ordered_resource($user_journey, $tt, $cat, $seven, $type);
        //$row[] = ['S&CF Learning Programs: '.strip_tags($vv)];
        //$row[] = ['Title'];
        foreach($data as $value) {
            $row[] = [strip_tags($vv), $value['title'],  $value['know_id']];
        }
      //  $row[] = [''];
      }
    }
  }

  function _continuous_learning_ordering_form_redirect(array &$form, FormStateInterface $form_state) {
    
   $val= $form_state->getValues();
    // dd( $val);
   $redirect =new RedirectResponse('learning-programs/'.$val['topic']);
    // $redirect =new RedirectResponse('admin/managing/manage-resource-order/learning-programs/'.$val['topic']);
   $redirect->send();
   
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $val= $form_state->getValues();
    $user_journey = isset($val['user_journey']) ? (int)$val['user_journey'] : 0;
    $topic = isset($val['topic']) ? (int)$val['topic'] : 0;
    $navigate = 0;
    $cat = 0;
    $win = 0;
    $inspire = 0;
    $seven = 0;
  
    foreach ($val['resource_items'] as $id => $item) {
        \Drupal::database()->merge('resource_content_order')
        ->key(array('nid' => $item['resource_id'],
        'user_journey' => $user_journey,
        'topic' => $topic,
        'seven_step' => $seven,
        'resource_cat' => $cat,
        'win' => $win,
        'navigate' => $navigate,
        'inspire' => $inspire
        ))
        ->fields(array(
          'weight' => $item['weight'],
          'timestamp' => time()
        ))
        ->execute();
    }
    \Drupal::messenger()->addMessage('Form has been saved successfully');
   
  }

}




// $val = $form_state['values'];
// $user_journey = isset($val['user_journey']) ? (int)$val['user_journey'] : 0;
// $topic = 0;
// $type = 'get_involved';
// $navigate = 0;
// $cat = 0;
// $win = 0;
// $inspire = 0;
// $seven = 0;
// $row = [];
// $row[] = ['S&CF Learning Programs', 'Title', 'KNOW ID'];
// $options =  scf_get_options('continuous_learning');
// foreach($options as $t => $v) {
//   foreach($v as $tt => $vv) {
//     if(!is_int($tt)) {
//       continue;
//     }
//     $topic_name = '';
//     $topic_name =  strip_tags($vv);
//     $data = [];
//     $data = _scfp_get_ordered_resource($user_journey, $tt, $cat, $seven, $type);
//     //$row[] = ['S&CF Learning Programs: '.strip_tags($vv)];
//     //$row[] = ['Title'];
//     foreach($data as $value) {
//         $row[] = [strip_tags($vv), $value['title'],  $value['know_id']];
//     }
//   //  $row[] = [''];
//   }
// }

// $filename = 'Navigate-Learning Programs-'.time().'.csv';
// scf_reports_array_to_csv_download($row, $filename, $delimiter=",");


