<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\resource_ordering_form.
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

class resource_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'resource_ordering_form';
  }
  // $journey_tid = 0, $topic_tid = 0, $cat = 0, $seven = 0
  public function buildForm(array $form, FormStateInterface $form_state,$journey_tid = 0, $topic_tid = 0, $cat = 0, $seven = 0) {
    /**
   * Inspire
   * $topic_tid -> Inspire Question
   * $cat -> Resource Category
   * $seven -> Not Applicable
   *
   * Win
   * $topic_tid -> Topics/Subtopics
   * $cat -> Win Category
   * $seven -> Not Applicable
   *
   * Lead
   * $topic_tid -> Topics/Subtopics
   * $cat -> Resource Category
   * $seven -> Not Applicable
   *
   * Lead + Business
   * $topic_tid -> Navigate our practice category
   * $cat -> Resource Category
   * $seven -> Seven step
   *
   * Navigate
   * $topic_tid -> Topics/Subtopics
   * $cat -> Not Applicable
   * $seven -> Not Applicable
   *
   */



    $vid = 'topics';
  //   $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,4,TRUE);


  //  dd($terms);

   $tag_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  $options = $options = [0 => '<none>'] +[];
  foreach ($tag_terms as $tag_term) {
   $options[$tag_term->tid] = $tag_term->name;
   }


   
    $form['topic'] = [
      '#type' => 'select',
      '#title' => 'Topics/Subtopics',
      // '#options' => ['0'=> $all_topics] + $options,
    '#options' =>  $options,
      // '#default_value' => $topic_tid,
      '#weight' => -10,
      // '#attributes' => array('onchange' => "form.submit('topics')"),
    ];
   
    // if($type == 'lead') {
    //   // Lead + Business
    //   if($topic_uuid == '0ae477df-c402-41dc-8c19-4b64df89b99f') {
    //     if($topic_tid > 0) {
    //       $seven_step_markup = '<ul>';
    //       $path = '';
    //       foreach($seven_step as $seven_id => $seven_val) {
    //         $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat.'/'.$seven_id)->toString());
    //       //  $path->send();

    //       //  $path = 'admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat.'/'.$seven_id;
    //         $seven_step_markup .='<li>'.l($seven_val, $path).'</li>';
    //       }
    //       $seven_step_markup .='</ul>';
    //         $form['seven_steps'] = [
    //           '#type' => 'item',
    //           '#title' => t('7 Building Block'),
    //           '#markup' => $seven_step_markup,
    //           ];
    //     }
  
    //     $cat_markup = '<ul>';
    //     $path = '';
    //     foreach($categoties as $cat_id => $cat_val) {
    //     $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven)->toString());
    //     //  $path = 'admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven;
    //       $cat_markup .='<li>'.l($cat_val, $path).'</li>';
    //     }
    //     $cat_markup .='</ul>';
    //     if($topic_tid > 0 && $seven > 0) {
    //       $form['resource_category'] = [
    //         '#type' => 'item',
    //         '#title' => t('Resource Category'),
    //         '#markup' => $cat_markup,
    //       ];
    //     }
    //   } else { // Lead not business
    //     if($topic_tid > 0) {
    //       $cat_markup = '<ul>';
    //       $path = '';
    //       foreach($categoties as $cat_id => $cat_val) {
    //       $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/0')->toString());
    //       //  $path = 'admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/0';
    //         $cat_markup .='<li>'.l($cat_val, $path).'</li>';
    //       }
    //       $cat_markup .='</ul>';
    //       $form['resource_category'] = [
    //         '#type' => 'item',
    //         '#title' => t('Resource Category'),
    //         '#markup' => $cat_markup,
    //         ];
    //     }
    //   }
    // }
   
    // if($type == 'inspire') {
    //     if($topic_tid > 0) {
    //       $cat_markup = '<ul>';
    //       $path = '';
    //       foreach($categoties as $cat_id => $cat_val) {
    //       $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven)->toString());
    //       //  $path = 'admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven;
    //         $cat_markup .='<li>'.l($cat_val, $path).'</li>';
    //       }
    //       $cat_markup .='</ul>';
    //       $form['resource_category'] = [
    //         '#type' => 'item',
    //         '#title' => t('Resource Category'),
    //         '#markup' => $cat_markup,
    //         ];
    //   }
    // }
   
    // if($type == 'win') {
    //   if($topic_tid > 0) {
    //     $cat_markup = '<ul>';
    //     $path = '';
    //     foreach($categoties as $cat_id => $cat_val) {
    //     $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven)->toString());
    //     //  $path = 'admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven;
    //       $cat_markup .='<li>'.l($cat_val, $path).'</li>';
    //     }
    //     $cat_markup .='</ul>';
    //     $form['resource_category'] = [
    //       '#type' => 'item',
    //       '#title' => t('Win category'),
    //       '#markup' => $cat_markup,
    //       ];
    //   } else {
    //     $cat_markup = '<ul>';
    //     $path = '';
    //     foreach($categoties as $cat_id => $cat_val) {
    //     $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven)->toString());
    //     //  $path = 'admin/managing/manage-resource-order/'.$journey_tid.'/'.$topic_tid.'/'.$cat_id.'/'.$seven;
    //       $cat_markup .='<li>'.l($cat_val, $path).'</li>';
    //     }
    //     $cat_markup .='</ul>';
    //     $form['resource_category'] = [
    //       '#type' => 'item',
    //       '#title' => t('Win category'),
    //       '#markup' => $cat_markup,
    //       ];
    //   }
    // }
   
    // $i=0;
    // $weight = -50;
    // $data = [];
    // //  $data = _scfp_get_ordered_resource($journey_tid, $topic_tid, $cat, $seven, $type);
    // foreach($data as $item) {
      $form['resource_items']['#tree'] = TRUE;
      $form['resource_items'][$i] = [
        'resource_id' => [
          '#type' => 'hidden',
          // '#default_value' => isset($item) ? $item['id'] : 0,
        ],
        'resource' =>[
          '#type' => 'textfield',
          // '#default_value' => isset($item) ? $item['title'].' [nid:'.$item['id'].']' : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#required' => FALSE,
          '#attributes' => ['readonly' => 'readonly'],
        ],
        'know_id' =>[
          '#type' => 'item',
          // '#markup' => isset($item) ? $item['know_id'] : '',
        ],

        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          // '#default_value' => $weight,
          '#delta' => 50,
          '#title_display' => 'invisible',
        ],
      ];
      // $i++;
      // $weight++;
    // }
   
    // $header = array(t('Resources'), t('KNOW ID'), t('Weight'));
    // $form['table'] = [
    //   '#type' => 'table',
    
    //   '#header' => $header,
    //   // '#rows' => $rows,
    //   // '#empty' => t('No content has been found.'),
    // ];
    // $form['actions']['redirect'] = array(
    //    '#type' => 'submit',
    //    '#value' => t('Submit'),
    //    '#submit' => array([$this,'_resource_ordering_form_redirect']),
    //    '#prefix' => '<div style="display:none;">',
    //    '#suffix' => '</div>',
    //  );
    // // if(count($data) > 0) {
    //  $form['actions']['save'] = array(
    //     '#type' => 'submit',
    //     '#value' => t('Save Changes'),
    //     // '#submit' => array([$this,'_resource_ordering_form_save']),
    //   );
    // // }
   
    $form['actions']['download_all'] = array(
       '#type' => 'submit',
       '#value' => t('Download CSV'),
      //  '#submit' => array([$this,'_resource_ordering_form_download_all']),
     );

   
     return $form;
  }




  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    // dd('asmcka');
    // $val = $form_state['values'];
    $val = $form_state->getValues();
    $user_journey_type = isset($val['user_journey_type']) ? $val['user_journey_type'] : '';

    $user_journey = isset($val['user_journey']) ? (int)$val['user_journey'] : 0;
    $topic = isset($val['topic']) ? (int)$val['topic'] : 0;
    $cat = isset($val['resource_cat']) ? (int)$val['resource_cat'] : 0;
    $seven = isset($val['seven_step']) ? (int)$val['seven_step'] : 0;
    $inspire = $win = $navigate = 0;
    dd($inspire);
    if($user_journey_type == 'inspire') {
      $inspire = $topic;
      $topic = 0;
      $seven = 0;
      $win = 0;
      $navigate = 0;
    }

    if($user_journey_type == 'win') {
      $win = $cat;
      $cat = 0;
      $seven = 0;
    }

    if($user_journey_type == 'lead') {
      $win = 0;
      $inspire = 0;
      $navigate = 0;
    }

    if($user_journey_type == 'navigate') {
      $navigate = $topic;
      $cat = 0;
      $win = 0;
      $inspire = 0;
      //$navigate = 0;
      $topic = 0;
      $seven = 0;
    }

    foreach ($val['resource_items'] as $id => $item) {
        db_merge('resource_content_order')
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
    Drupal::messenger()->addMessage(t('Form has been saved successfully'));




  }



  function _resource_ordering_form_download_all(&$form, &$form_state) {
    // dd('sdmnvjfebf');
    $val = $form_state->getValues();
    // $val = $form_state['values'];
    // dd($val);
    $user_journey_type = isset($val['user_journey_type']) ? $val['user_journey_type'] : '';
    $user_journey = isset($val['user_journey']) ? (int)$val['user_journey'] : 0;
    $topic = isset($val['topic']) ? (int)$val['topic'] : 0;
    $cat = isset($val['resource_cat']) ? (int)$val['resource_cat'] : 0;
    $seven = isset($val['seven_step']) ? (int)$val['seven_step'] : 0;
    $inspire = $win = $navigate = 0;
    dd($inspire);
    $journey_name = scf_get_term_name_from_tid($user_journey);
    dd($journey_name);
    $data = [];
    switch ($user_journey_type) {
      case 'inspire':
          $row = [];
          $row[] = ['Inspire questions', 'Resource Category', 'Resource Title', 'KNOW ID'];
          $inspire = $topic;
          $topic = 0;
          $seven = 0;
          $win = 0;
          $navigate = 0;
          $inspires = scf_get_options('topics');
          $exclude_tabs = [];
          // Essentials
          $exclude_tabs[] = 'ca7036bf-c418-4839-8615-b3962191d6a5';
          //How-to Guides
          $exclude_tabs[] = '888c621d-895a-449e-b1d3-3d0bf796d7f7';
          $categoties =  get_all_terms_by_vocab('resource_category', $exclude_tabs);
          foreach($inspires as $i => $v) {
            foreach($v as $inspire => $val) {
              if(empty($inspire) || $inspire == 0 || !is_int($inspire)) {
                continue;
              }
              $inspire_name = '';
              $inspire_name = strip_tags($val);
              foreach($categoties as $rcat => $rcat_val) {
                $data = [];
                $data = _scfp_get_ordered_resource($user_journey, $inspire, $rcat, $seven, $user_journey_type);
                foreach($data as $val) {
                    $row[] = [$inspire_name, $rcat_val, $val['title'], $val['know_id']];
                }
              }
            }
          }
          $filename = $journey_name.'-resources-'.time().'.csv';
          scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
        break;
        case 'win':
          $win = $cat;
          $cat = 0;
          $seven = 0;
          $row[] = ['Topics/Subtopics', 'Win Category', 'Resource Title', 'KNOW ID'];

          $categoties =  get_all_terms_by_vocab('win_category');

          $topics = [];
          $topics = scf_get_options('topics');
          $topics = ['all_topics' => [0 => 'All Topics']] + $topics;
          foreach($topics as $t => $v) {
              foreach($v as $topic => $val) {
                if(!is_int($topic)) {
                  continue;
                }
                $topic_name = '';
                $topic_name =  strip_tags($val);
                $data = [];
                foreach($categoties as $win_cat => $win_title) {
                  $data = [];
                  $data = _scfp_get_ordered_resource($user_journey, $topic, $win_cat, $seven, $user_journey_type);
                  foreach($data as $val) {
                      $row[] = [$topic_name, $win_title, $val['title'], $val['know_id']];
                  }
              }
            }
        }
        $filename = $journey_name.'-resources-'.time().'.csv';
        scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
        break;
        case 'lead':
          $row[] = ['Topics/Subtopics', '7 Building Block', 'Resource Category', 'Resource Title', 'KNOW ID'];
          $win = 0;
          $inspire = 0;
          $navigate = 0;
          $lead_tid = scf_get_tid_from_uuid('0ae477df-c402-41dc-8c19-4b64df89b99f');
          $topics = $categoties = [];
          $topics = scf_get_options('topics');
          $exclude_tabs = [];
          //Speech & Workshop Materials
          $exclude_tabs[] = '1671af34-2268-4e6e-b9ae-179b558ddeb9';
          //Articles & Perspectives
          $exclude_tabs[] = '4b0dc1f2-05df-46dd-870a-c6a7a45f25bf';

          $categoties =  get_all_terms_by_vocab('resource_category', $exclude_tabs);
          foreach($topics as $t => $v) {
              foreach($v as $topic => $val) {
                if(!is_int($topic)) {
                  continue;
                }
                $topic_name = '';
                $topic_name =  strip_tags($val);
                $data = [];

                // if(!empty($topic_name)){
                //   $row[] = ['Topics/Subtopics: '.$topic_name];
                // }


                if($lead_tid == $topic) {
                  $seven_step =  get_all_terms_by_vocab('7_building_block');
                  foreach ($seven_step as $seven_id => $seven_title)  {
                    //$row[] = ['7 Building Block: '.$seven_title];
                    foreach($categoties as $r_cat => $r_title) {
                      $data = [];
                      $data = _scfp_get_ordered_resource($user_journey, $topic, $r_cat, $seven_id, $user_journey_type);
                      //$row[] = ['Resource Category: '.$r_title];
                    //  $row[] = ['Resource Title'];
                      foreach($data as $val) {
                          $row[] = [$topic_name, $seven_title, $r_title, $val['title'], $val['know_id']];
                      }
                      //$row[] = [''];
                    }
                  }
                } else {
                  foreach($categoties as $r_cat => $r_title) {
                    $data = [];
                    $data = _scfp_get_ordered_resource($user_journey, $topic, $r_cat, 0, $user_journey_type);
                    //$row[] = ['Resource Category: '.$r_title];
                    //$row[] = ['Resource Title'];
                    foreach($data as $val) {
                        $row[] = [$topic_name, '', $r_title, $val['title'], $val['know_id']];
                    }
                    //$row[] = [''];
                  }
              }
            }
          }

        $filename = $journey_name.'-resources-'.time().'.csv';
        scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
        dd('cdsjbchej');

        break;
        case 'navigate':
          $navigate = $topic;
          $cat = 0;
          $win = 0;
          $inspire = 0;
          $topic = 0;
          $seven = 0;
          $row[] = ['Navigate our practice category',  'Resource Title' , 'KNOW ID'];
          $options = scf_get_options('navigate_our_practice');
          foreach($options as $t => $v) {
              foreach($v as $topic => $val) {
                if(!is_int($topic)) {
                  continue;
                }
                $topic_name = '';
                $topic_name =  strip_tags($val);
                $data = [];

                // if(!empty($topic_name)){
                //   $row[] = ['Navigate our practice category: '.$topic_name];
                // }
                $data = [];
                $data = _scfp_get_ordered_resource($user_journey, $topic, 0, 0, $user_journey_type);
                //$row[] = ['Resource Title'];
                foreach($data as $val) {
                    $row[] = [$topic_name, $val['title'], $val['know_id']];
                }
              //  $row[] = [''];
              }
          }
          $filename = $journey_name.'-resources-'.time().'.csv';
          scf_reports_array_to_csv_download($row, $filename, $delimiter=",");
        break;
    }
  }



  function _resource_ordering_form_redirect(&$form, &$form_state) {
    // dd('kfjwfwf');
    // $val = $form_state['values'];
    $val = $form_state->getValues();
    dd( $val);
    $seven = isset($val['seven_step']) ? $val['seven_step'] : 0;
    $cat = isset($val['resource_cat']) ? $val['resource_cat'] : 0;
    $topic= isset($val['topic']) ? $val['topic'] : 0;

    // $topic_uuid = scf_get_uuid_from_tid($topic);
    if($topic_uuid == '0ae477df-c402-41dc-8c19-4b64df89b99f') {
      $seven_step =  get_all_terms_by_vocab('7_building_block');
      $seven = ($seven > 0) ? $seven : key($seven_step);
      // dd($seven);
    } else {
      $seven = 0;
    }


    // $path = 'admin/managing/manage-resource-order/'.$val['user_journey'].'/'.$topic.'/'.$cat.'/'.$seven;
    // $form_state['redirect'] = $path;
    $path = new RedirectResponse(Url::fromUserInput('admin/managing/manage-resource-order/'.$val['user_journey'].'/'.$topic.'/'.$cat.'/'.$seven)->toString());
    $path->send();



  }



  /**
 * Get All terms from vacb by name
 * @param String $vcab_machine_name
 *
 * @return Array
 */
function get_all_terms_by_vocab($vcab_machine_name, $exclude = ['']) {
  $terms = [];
  if(!empty($vcab_machine_name)) {
      $query = db_select('taxonomy_term_data', 'td');
      $query->join('taxonomy_vocabulary', 'tv', 'tv.vid = td.vid');
      $query->condition('tv.machine_name', $vcab_machine_name);
      $query->condition('td.uuid', $exclude, 'NOT IN');
      $query->fields('td',array('tid','name', 'weight'))
          ->orderBy('weight', 'ASC');
      $result = $query->execute();
      while ($record = $result->fetchAssoc()) {
          $terms[$record['tid']] = $record['name'];

      }
  }
  return $terms;
}


}


