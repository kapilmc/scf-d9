<?php
 
/**
 * @file
 * @Contains Drupal\scfp_miscellaneous_blocks\Controller\scfp_miscellaneous_blocksController.
 */

namespace Drupal\scfp_miscellaneous_blocks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\myblock\Entity\MyBlock;

use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AddCommand;



use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


use Drupal\Core\Render\Markup;

/**
 * Implement Demo class operations.
 */
class scfp_miscellaneous_blocksController extends ControllerBase {

 

/**
* autocomplete_get_involved
* @return JSON
*/
function scfp_get_meet_our_people_links_autocomplete() {

// dd('svdfbjgh');



$string = \Drupal::request()->query->get('q');
// dd($string);
  // $string = 'Mo';
  $matches = array();
  if ($string) {
    $data = [];
    $connection = \Drupal\Core\Database\Database::getConnection();
    $query = $connection->select('node_field_data', 'n');
    $query->fields('n', ['nid', 'title'])
        ->condition('n.type', 'resource', '=')
        ->condition('n.status', 1, '=')
        // ->condition('n.title', '%' . db_like($string) . '%', 'LIKE')
        ->condition('n.title', $connection->escapeLike($string).'%', 'LIKE')
        ->range(0, 20);
      $items = $query->execute();
    //   dd($items);
//       foreach ($items as $item) {
//         // dd($item);
//                   $matches[$item->title.' [nid:'.$item->nid.']'] = check_plain($item->title).' [nid:'.$item->nid.']';
//               }
// dd($matches);




$sort_array = array();
foreach ($items as $item) {

  // $fname = is_object($item) ? $item->title : '';
  // $lname = is_object($item) ? $item->field_last_name_value : '';
  // $sort_array[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;



  $title = is_object($item) ? $item->title : '';

//   $lname = is_object($item) ? $item->field_last_name_value : '';

  $sort_array[$title . ' '. ' [nid:' . $item->nid . ']'] = $title;
}

// Sort topics by title and leave only 10 of them.
asort($sort_array);
$sort_array = array_slice($sort_array, 0, 20, TRUE);
// dd($sort_array);
foreach ($sort_array as $key => $value) {

  $check_value = \Drupal\Component\Utility\Html::escape($value);
 $matches[] = ['value'=>$key,'label'=>$check_value];
//  dd($matches);
}
  
    
  }
  
//   dd($matches);
 
  return new JsonResponse($matches);

}

 
  /**
   * remove_item_from_meet_our_people_links
   * @param  integer $nid node id
   */
  public function remove_item_from_meet_our_people_links($nid = 0) {
    \Drupal::database()->delete('scf_meet_our_people_links')
    ->condition('nid', $nid)
    ->execute();

    $tempstore = \Drupal::service('tempstore.private')->get('scfp_meet_our_people');
    $tempstore->set('num_rows_scfp_meet_our_people', $tempstore->get('num_rows_scfp_meet_our_people') - 1);

    $redirect =new RedirectResponse('/admin/content/manage/meet-our-people-links');
    $redirect->send();
  


  }
  





        /**
 * Remove_item_hit_core_team.
 *
 * @param int $id
 */
function remove_item_get_help($id = 0) {
  $query = \Drupal::database()->delete('scfp_get_help_ordering');
  $query->condition('id', $id);
  $query->execute();

  $tempstore = \Drupal::service('tempstore.private')->get('scfp_get_help');
  $tempstore->set('num_rows_scfp_get_help', $tempstore->get('num_rows_scfp_get_help=') - 1);

  $redirect =new RedirectResponse('/admin/content/manage/get-help-block');
  $redirect->send();
  
    
}  







function autocomplete_get_involved() {

$string = \Drupal::request()->query->get('q');
// dd($string);
  // $string = 'Mo';
  $matches = array();
  if ($string) {
    $data = [];
    $connection = \Drupal\Core\Database\Database::getConnection();
    $query = $connection->select('node_field_data', 'n');
    $query->fields('n', ['nid', 'title'])
        ->condition('n.type', 'resource', '=')
        ->condition('n.status', 1, '=')
        ->condition('n.title', $connection->escapeLike($string).'%', 'LIKE')
        ->range(0, 20);
      $items = $query->execute();

$sort_array = array();
foreach ($items as $item) {
  $title = is_object($item) ? $item->title : '';

  $sort_array[$title . ' '. ' [nid:' . $item->nid . ']'] = $title;
}

// Sort topics by title and leave only 10 of them.
asort($sort_array);
$sort_array = array_slice($sort_array, 0, 20, TRUE);

foreach ($sort_array as $key => $value) {

  $check_value = \Drupal\Component\Utility\Html::escape($value);
 $matches[] = ['value'=>$key,'label'=>$check_value];

}
  
    
  }
  


  return new JsonResponse($matches);

}
}























// // ---------------


//   // $string = arg(3);
//   // $matches = array();
//   // if ($string) {


//     // dd('svdfbbnnnnnnnnnnnnnnnnnnnnndfjgfdvh');



// $string = \Drupal::request()->query->get('q');
// // dd($string);
//   // $string = 'Monkjnkjn';
//   $matches = array();
//   if ($string) {
//     $data = [];
//     // $connection = \Drupal\Core\Database\Database::getConnection();
//     // $journey_tid = scf_get_tid_from_uuid('7fe01294-357f-4f72-86c5-80293fef361e');
//     $query = \Drupal::database()->select('node_field_data', 'n');
//     $query->join('field_data_field_user_journey', 'j','j.entity_id = n.nid');
//     $query->fields('n', ['nid', 'title']);
//     $query->condition('n.type', 'resource', '=');
//     $query->condition('n.status', 1, '=');
//     // $result = $query->execute()->fetchAll();
//     // dd($result);

//       // ->condition('n.title', '%' . db_like($string) . '%', 'LIKE')
//       // $query->condition('n.title', '%'.$connection->escapeLike($string).'%', 'LIKE');


//       $query->condition('j.field_user_journey_tid', $journey_tid, '=');
//       $query->range(0, 20);
//       // dd($query);

//       // $result = $query->execute()->fetchAll();
//       // dd($result);
//     $items = $query->execute();
//     foreach ($items as $item) {
//         $matches[$item->title.' [nid:'.$item->nid.']'] = check_plain($item->title).' [nid:'.$item->nid.']';
//     }
//   }
//   // drupal_json_output($matches);
//   return new JsonResponse($matches);
// }


// }


//   // //hitpage coreteam
//   // public function autocomplete_item_core_team_hit(Request $request) {
    
//   //   $string = \Drupal::request()->query->get('q');
//   //   $matches = array();
//   //   if ($string) {
//   //     $data = [];
//   //     $connection = \Drupal\Core\Database\Database::getConnection();
//   //     $query = $connection->select('scf_hit_team_ordering', 'r');
//   //     $query->join('node', 'n', 'r.nid = n.nid');
//   //     $query->addField('r', 'nid', 'nid');
//   //     $query->condition('r.key_contact', 0);
//   //     $result = $query->execute()->fetchAll();
//   //     foreach ($result as $key => $item) {
//   //         $data[] = $item->nid;
      
//   //     }


//   //     $query = $connection->select('node_field_data', 'n');
//   //     $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
//   //     $query->leftjoin('node__field_expert_search', 'f', 'f.entity_id = n.nid');
//   //     $query->fields('l', ['field_last_name_value', 'field_last_name_value']);
//   //     $query->fields('f', ['field_expert_search_value', 'field_expert_search_value']);
//   //     $query->fields('n', ['nid', 'nid']);
//   //     $query->fields('n', ['title', 'title']);
//   //     $query->condition('n.type', ['experts'], 'IN');
//   //     $query->condition('f.field_expert_search_value', $connection->escapeLike($string).'%', 'LIKE');
//   //     $items = $query->execute()->fetchAll();
//   //     // dd($items);
  

//   //     $sort_array = array();
//   //     foreach ($items as $item) {
//   //       $fname = is_object($item) ? $item->title : '';
//   //       $lname = is_object($item) ? $item->field_last_name_value : '';
//   //       $sort_array[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;
//   //     }

//   //     // Sort topics by title and leave only 10 of them.
//   //     asort($sort_array);
//   //     $sort_array = array_slice($sort_array, 0,20, TRUE);
//   //     // dd($sort_array);
//   //     foreach ($sort_array as $key => $value) {

//   //       $check_value = \Drupal\Component\Utility\Html::escape($value);
//   //       $matches[] = ['value'=>$key,'label'=>$check_value];
//   //     }
        
          
//   //   }
        

        
//   //       return new JsonResponse($matches);
//   // }