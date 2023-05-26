<?php
 
/**
 * @file
 * @Contains Drupal\scf_mcsi\Controller\scf_mcsiController.
 */

namespace Drupal\scf_mcsi\Controller;

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


// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Request;
// use Drupal\Component\Utility\Xss;
// use Drupal\Core\Entity;
// use Symfony\Component\HttpFoundation;


use Drupal\Core\Render\Markup;

/**
 * Implement scf_mcsi class operations.
 */
class scf_mcsiController extends ControllerBase {

  public function first_alter_autocomplete() {
      // dd('scdbjhjbcds');


      $string = \Drupal::request()->query->get('q');
     
        $string = 'ak';
        $matches = array();
        if ($string) {
          $data = [];
          $connection = \Drupal\Core\Database\Database::getConnection();
          $query = $connection->select('scf_mcsi_team_ordering', 'r');
          $query->join('node', 'n', 'r.nid = n.nid');
          $query->fields('r', ['nid', 'nid']);
          // $query->condition('n.status', 1);
          $query->condition('r.first_alert', 0);
          $result = $query->execute()->fetchAll();
         foreach ($result as $key => $item) {
             $data[] = $item->nid;
          // dd( $data);
          }


          $query = $connection->select('node_field_data', 'n');
          $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
          $query->leftjoin('node__field_expert_search', 'f', 'f.entity_id = n.nid');
          $query->fields('l', ['field_last_name_value', 'field_last_name_value']);
          $query->fields('f', ['field_expert_search_value', 'field_expert_search_value']);
          $query->fields('n', ['nid', 'nid']);
          $query->fields('n', ['title', 'title']);
          $query->condition('n.type', ['experts'], 'IN');
          $query->condition('f.field_expert_search_value', $connection->escapeLike($string).'%', 'LIKE');
          $items = $query->execute()->fetchAll();
        
        

          $sort_array = array();
          foreach ($items as $item) {
            $fname = is_object($item) ? $item->title : '';
            $lname = is_object($item) ? $item->field_last_name_value : '';
            $sort_array[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;
          }

          // Sort topics by title and leave only 10 of them.
          asort($sort_array);
          // $sort_array = array_slice($sort_array, 0, 15, TRUE);
          // dd($sort_array);
          foreach ($sort_array as $key => $value) {

            $check_value = \Drupal\Component\Utility\Html::escape($value);
           $matches[] = ['value'=>$key,'label'=>$check_value];
          }
        
          
        }
        
        // dd($matches);
       
        return new JsonResponse($matches);



    }

      /**
   * Remove_item_mcsi_core_team.
   *
   * @param int $id
   */
  function remove_item_mcsi_core_team($id = 0) {

    $query = \Drupal::database()->delete('scf_mcsi_team_ordering');
    $query->condition('id', $id);
    $query->execute();

    $tempstore = \Drupal::service('tempstore.private')->get('scf_mcsi');
    $tempstore->set('num_rows_mcsi_core_team_page', $tempstore->get('num_rows_mcsi_core_team_page') - 1);

    $redirect =new RedirectResponse('/admin/mcsi/core-team');
    $redirect->send();

    
  }



               /**
   * Remove_item_hit_core_team.
   *
   * @param int $id
   */
  function remove_item_mcsi_first_alert($id = 0) {
    $query = \Drupal::database()->delete('scf_mcsi_team_ordering');
    $query->condition('id', $id);
    $query->execute();

    $tempstore = \Drupal::service('tempstore.private')->get('scf_mcsi');
    $tempstore->set('num_rows_mcsi_first_alert_page', $tempstore->get('num_rows_mcsi_first_alert_page') - 1);

    $redirect =new RedirectResponse('/admin/mcsi/core-team/first-alert');
    $redirect->send();
    
      
  } 
   

    public function mcsi_autocomplete(Request $request) {
      //  dd('KJKKJKDD');
      // //get node id from header
      // $previous_path = $request->headers->get('referer');
      // $exp = explode('/' , $previous_path);
      // $count = count($exp);
  
      // $arr = [];
      // for ($i=0; $i < $count; $i++) { 
          
      //     if($exp[$i]=='node' && $exp[$i+2] == 'edit' || $exp[$i-1]=='node' && $exp[$i+1] == 'edit' || $exp[$i]=='edit' && $exp[$i-2] == 'node'){
      //       $arr[] = $exp[$i];
      //     }
      // }
  
      $string = \Drupal::request()->query->get('q');
      // dd($string);
        // $string = 'Ka';
        $matches = array();
        if ($string) {
          $data = [];
          $connection = \Drupal\Core\Database\Database::getConnection();
          $query = $connection->select('scf_mcsi_team_ordering', 'r');
          $query->join('node', 'n', 'r.nid = n.nid');
          $query->fields('r', ['nid', 'nid']);
          // $query->condition('n.status', 1);
          $query->condition('r.first_alert', 0);
          $result = $query->execute()->fetchAll();
         foreach ($result as $key => $item) {
             $data[] = $item->nid;
          
          }


          $query = $connection->select('node_field_data', 'n');
          $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
          $query->leftjoin('node__field_expert_search', 'f', 'f.entity_id = n.nid');
          $query->fields('l', ['field_last_name_value', 'field_last_name_value']);
          $query->fields('f', ['field_expert_search_value', 'field_expert_search_value']);
          $query->fields('n', ['nid', 'nid']);
          $query->fields('n', ['title', 'title']);
          $query->condition('n.type', ['experts'], 'IN');
          $query->condition('f.field_expert_search_value',$connection->escapeLike($string).'%', 'LIKE');
          $items = $query->execute()->fetchAll();
          // dd($items);
          // foreach ($items as $item) {
           
          //   $fname = is_object($item) ? $item->title : '';
            
          //   $lname = is_object($item) ? $item->field_last_name_value : '';
          //   // dd($lname);
          //   if (!in_array($item->nid, $data)) {
          //     $matches[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;
          //   }
          // }

          $sort_array = array();
          foreach ($items as $item) {
            $fname = is_object($item) ? $item->title : '';
            $lname = is_object($item) ? $item->field_last_name_value : '';
            $sort_array[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;
          }

          // Sort topics by title and leave only 10 of them.
          asort($sort_array);
          $sort_array = array_slice($sort_array, 0, 15, TRUE);
          // dd($sort_array);
          foreach ($sort_array as $key => $value) {

            $check_value = \Drupal\Component\Utility\Html::escape($value);
           $matches[] = ['value'=>$key,'label'=>$check_value];
          }
        
          
        }
        
        // dd($matches);
       
        return new JsonResponse($matches);
    }






}

