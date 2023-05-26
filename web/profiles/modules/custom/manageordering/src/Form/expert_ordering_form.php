<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\expert_ordering_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

class expert_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'expert_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {

    $vid = 'topics';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,5,TRUE);
   


    foreach ($terms as $term) {
        // dd($term);
        $data[]= array(
            
        'name'=> $term->name->value,
        );
    }
// dd($data);

    // $topic_uuid = scf_get_uuid_from_tid($topic_tid);
    // $all_topics = '<none>';
    // $topics = scf_get_options('topics');
    // $business_strategy_id = scf_get_tid_from_uuid('0ae477df-c402-41dc-8c19-4b64df89b99f');
    // $fa = variable_get('meet_our_people_first_alert', 0);
    // $expert = variable_get('meet_our_people_expert', 0);
  
    // $validate_seven_steps = [0];
    // if($topic_tid == $business_strategy_id) {
    //   $seven_step =  get_all_terms_by_vocab('7_building_block');
    //   $seven_step = [$topic_tid => 'Overview'] + $seven_step;
    //   $validate_seven_steps = array_keys($seven_step + [0]);
    // }
  
    // $val_topics = [0] + get_all_terms_by_vocab('topics');
   
    // if(count(array_filter(arg(), function($value) { return !is_null($value) && $value !== ''; })) != 7) {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/0/0/topic');
    // }
  
    // if(arg(3) == 0) {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$fa.'/0/0/topic');
    // }
  
    // if(!is_numeric(arg(3)) || !is_numeric(arg(4)) || !is_numeric(arg(5)) || arg(6) != 'topic') {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/0/0/topic');
    // }
  
    // if(!in_array(arg(4), array_keys($val_topics))) {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/0/0/topic');
    // }
  
    // if(!in_array(arg(5), $validate_seven_steps)) {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/'.$topic_tid.'/0/topic');
    // }
    
    // if(($topic_tid == $business_strategy_id) &&  ($inspire_tid == 0)) {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/'.$topic_tid.'/'.$business_strategy_id.'/topic');
    // }
  
  
    // if($cat_tid > 0) {
    //   $cat_name = scf_get_term_name_from_tid($cat_tid);
    //   drupal_set_title('Manage '.$cat_name. ' ordering');
    //   $expert_cats = [$fa, $expert];
    //   if(!in_array($cat_tid, $expert_cats) && $topic_tid == $business_strategy_id) {
    //     if(($topic_tid == $business_strategy_id) &&  ($inspire_tid != $business_strategy_id)) {
    //       drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/'.$topic_tid.'/'.$business_strategy_id.'/topic');
    //     }
    //     $inspire_tid = $business_strategy_id;
    //   } 
    // }
  
    // if(count(arg()) > 7) {
    //   drupal_goto('admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/'.$topic_tid.'/'.$inspire_tid.'/topic');
    // }
    
    // $form['cat'] = [
    //     '#type' => 'hidden',
    //     '#value' => (int)$cat_tid,
    //   ];
    // $form['seven'] = [
    //       '#type' => 'hidden',
    //       '#value' => (int)$inspire_tid,
    //   ];
  
    // if($type == 'topic') {
      $form['topic'] = [
        '#type' => 'select',
        '#title' => t('Choose topics/subtopics'),
        // '#options' => ['0'=> 'All Topics'] + $topics,
        '#options' => ['0'=> 'All Topics'] + $data,
        '#default_value' => (int)$topic_tid,
        '#weight' => -10,
        // '#attributes' => array('onchange' => "form.submit('topics')"),
      ];
    //   if($topic_uuid == '0ae477df-c402-41dc-8c19-4b64df89b99f' && in_array($cat_tid, $expert_cats)) {
    //     if($topic_tid > 0) {
    //       $seven_step_markup = '<ul>';
    //       $path = '';
    //       foreach($seven_step as $seven_id => $seven_val) {
    //         $path = 'admin/managing/manage-first-alert-and-expert-order/'.$cat_tid.'/'.$topic_tid.'/'.$seven_id.'/topic';
    //         $seven_step_markup .='<li>'.l($seven_val, $path).'</li>';
    //       }
    //       $seven_step_markup .='</ul>';
    //         $form['seven_steps'] = [
    //           '#type' => 'item',
    //           '#title' => t('Strategy Methods'),
    //           '#markup' => $seven_step_markup,
    //           ];
    //     }
    //   }
    // }
  
    
    $form['type'] = [
        '#type' => 'hidden',
        '#value' => $type,
      ];
  
      $data = [];
      $i=0;
      $weight = -5000;
      $attr = ['readonly' => 'readonly'];
      if($topic_uuid == '0ae477df-c402-41dc-8c19-4b64df89b99f') {
        // $data = _scfp_get_ordered_expert($cat_tid , $inspire_tid,  0, $type);
      } else {
        // $data = _scfp_get_ordered_expert($cat_tid , $topic_tid,  $inspire_tid, $type);
      }
    //   foreach($data as $item) {
          $form['resource_items']['#tree'] = TRUE;
          $form['resource_items'][$i] = [
            'resource_id' => [
              '#type' => 'hidden',
              '#default_value' => isset($item) ? $item['id'] : 0,
            ],
            'enabled' => [
              '#type' => 'checkbox',
              '#title' => '&nbsp;',
              '#default_value' => isset($item) ? $item['enabled'] : 1,
            ],
            'resource' =>[
              '#type' => 'textfield',
              '#default_value' => isset($item) ? $item['title'].' [nid:'.$item['id'].']' : '',
              '#size' => 60,
              '#maxlength' => 255,
              '#required' => FALSE,
              '#attributes' => $attr,
            ],
            'role' =>[
              '#type' => 'markup',
              '#markup' => isset($item) ? $item['role'] : '',
            ],
            'weight' => [
              '#type' => 'weight',
              '#title' => t('Weight'),
              '#default_value' => $weight,
              '#delta' => 5000,
              '#title_display' => 'invisible',
            ],
          ];
          $i++;
          $weight++;
        // }
  
    $form['actions']['redirect'] = array(
       '#type' => 'submit',
       '#value' => t('Submit'),
    //    '#submit' => array('_expert_ordering_form_redirect'),
       '#prefix' => '<div style="display:none;">',
       '#suffix' => '</div>',
     );
  
    //  if(count($data) > 0) {
     $form['actions']['save'] = array(
        '#type' => 'submit',
        '#value' => t('Save Changes'),
        // '#submit' => array('_expert_ordering_form_save'),
      );
    
  
    //  }
     $form['actions']['download_all'] = array(
        '#type' => 'submit',
        '#value' => t('Download CSV'),
        // '#submit' => array('_expert_ordering_form_download_all'),
      );
    return $form;


















  }




  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {


}


}