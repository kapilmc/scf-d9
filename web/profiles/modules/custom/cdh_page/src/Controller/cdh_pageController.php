<?php
 
/**
 * @file
 * @Contains Drupal\cdh_page\Controller\cdh_pageController.
 */

namespace Drupal\cdh_page\Controller;

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

use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity;
use Symfony\Component\HttpFoundation;


use Drupal\Core\Render\Markup;

/**
 * Implement scf_mcsi class operations.
 */
class cdh_pageController extends ControllerBase {


     /**
      * Autocomplete_item_from_hot_topics.
     *
     * @return JSON
     */
    public function autocomplete_item_cdh_team_cdh(){
      $string = \Drupal::request()->query->get('q');
      $matches = array();
      if ($string) {
        $data = [];
        $connection = \Drupal\Core\Database\Database::getConnection();
        $query = $connection->select('cdh_team_ordering', 'r');
        $query->join('node', 'n', 'r.nid = n.nid');
        $query->addField('r', 'nid', 'nid');
        $query->condition('r.key_contact', 0);
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
        $sort_array = array_slice($sort_array, 0,20, TRUE);
        foreach ($sort_array as $key => $value) {

          $check_value = \Drupal\Component\Utility\Html::escape($value);
         $matches[] = ['value'=>$key,'label'=>$check_value];
        }
      
        
      }
      return new JsonResponse($matches);
  }

    /**
  * remove_item_cdh_cdh_team.
    *
  * @param int $id
    */
  public function remove_item_cdh_cdh_team($id = 0){
    $query = \Drupal::database()->delete('cdh_team_ordering');
    $query->condition('id', $id);
    $query->execute();
    
    $tempstore = \Drupal::service('tempstore.private')->get('cdh_page');
    $tempstore->set('num_rows_cdh_team_page', $tempstore->get('num_rows_cdh_team_page') - 1);
    $redirect =new RedirectResponse('/admin/cdh-page/cdh-team');
    $redirect->send();
  }

    /**
  * remove_item_cdh_analyst.
    *
  * @param int $id
  */
  public function remove_item_cdh_analyst($id = 0){
    $query = \Drupal::database()->delete('cdh_analyst_support_ordering');
    $query->condition('id', $id);
    $query->execute();
    
    $tempstore = \Drupal::service('tempstore.private')->get('cdh_page');
    $tempstore->set('num_rows_cdh_analyst_page', $tempstore->get('num_rows_cdh_analyst_page') - 1);
    $redirect =new RedirectResponse('/admin/cdh-page/cdh-team/cdh-analyst-support');
    $redirect->send();
  }
  
 /**
  * remove_item_cdh_first_alert.
    *
  * @param int $id
  */
  public function remove_item_cdh_first_alert($id = 0){
    $query = \Drupal::database()->delete('cdh_first_alert_orderingg');
    $query->condition('id', $id);
    $query->execute();
    
    $tempstore = \Drupal::service('tempstore.private')->get('cdh_page');
    $tempstore->set('num_rows_cdh_first_alert_page', $tempstore->get('num_rows_cdh_first_alert_page') - 1);
    $redirect =new RedirectResponse('/admin/cdh-page/cdh-team/first-alert');
    $redirect->send();
  }
       /**
      * Autocomplete_item_from_hot_topics.
     *
     * @return JSON
     */
    
  public function autocomplete_item_cdh_first_alert (){

    $string = \Drupal::request()->query->get('q');
    $matches = array();
    if ($string) {
      $data = [];
      $connection = \Drupal\Core\Database\Database::getConnection();
      $query = $connection->select('cdh_first_alert_orderingg', 'r');
      $query->join('node', 'n', 'r.nid = n.nid');
      $query->addField('r', 'nid', 'nid');
      $query->condition('r.key_contact', 0);
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
      $sort_array = array_slice($sort_array, 0,20, TRUE);
      foreach ($sort_array as $key => $value) {

        $check_value = \Drupal\Component\Utility\Html::escape($value);
        $matches[] = ['value'=>$key,'label'=>$check_value];
      }
    
      
    }
  
    return new JsonResponse($matches);
  }

  public function autocomplete_item_cdh_analyst(){

  //   $string = arg(6);
  // $matches = array();
  // if ($string) {
  //   $data = [];
  //   $query = db_select('cdh_analyst_support_ordering', 'r');
  //   $query->join('node', 'n', 'r.nid = n.nid');
  //   $query->addField('r', 'nid', 'nid');
  //   $query->condition('n.status', 1);
  //   $query->condition('r.key_contact', 0);
  //   $result = $query->execute()->fetchAll();
  //   foreach ($result as $key => $item) {
  //     $data[] = $item->nid;
  //   }
  //   $query = db_select('node', 'n');
  //   $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
  //   $query->leftjoin('field_data_field_expert_search', 'f', 'f.entity_id = n.nid');
  //   $query->addField('l', 'field_last_name_value', 'field_last_name_value');
  //   $query->addField('f', 'field_expert_search_value', 'field_expert_search_value');
  //   $query->addField('n', 'nid', 'nid');
  //   $query->addField('n', 'title', 'title');
  //   $query->condition('n.type', ['experts'], 'IN')
  //     ->condition('n.status', 1)
  //     ->condition('f.field_expert_search_value', db_like($string) . '%', 'LIKE');
  //   $items = $query->execute();
  //   foreach ($items as $item) {
  //     $fname = is_object($item) ? $item->title : '';
  //     $lname = is_object($item) ? $item->field_last_name_value : '';
  //     if (!in_array($item->nid, $data)) {
  //       $matches[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = check_plain($fname . ' ' . $lname);
  //     }
  //   }
    //===================

    $string = \Drupal::request()->query->get('q');
    $string = 's';
    $matches = array();
    if ($string) {
      $data = [];
      $connection = \Drupal\Core\Database\Database::getConnection();
      $query = $connection->select('cdh_analyst_support_ordering', 'r');
      $query->join('node', 'n', 'r.nid = n.nid');
      $query->addField('r', 'nid', 'nid');
      $query->condition('r.key_contact', 0);
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
      $sort_array = array_slice($sort_array, 0,20, TRUE);
      foreach ($sort_array as $key => $value) {

        $check_value = \Drupal\Component\Utility\Html::escape($value);
        $matches[] = ['value'=>$key,'label'=>$check_value];
      }
    
      
    }
  
    return new JsonResponse($matches);
  }

}

