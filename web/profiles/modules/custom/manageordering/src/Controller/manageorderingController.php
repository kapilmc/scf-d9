<?php
 
/**
 * @file
 * @Contains Drupal\manageordering\Controller\manageorderingController.
 */

namespace Drupal\manageordering\Controller;

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
 * Implement manageordering class operations.
 */
class manageorderingController extends ControllerBase {




  /**
  * Autocomplete_item_from_hot_topics
  * @return JSON
  */
  function autocomplete_item_from_hot_topics() {


    $string = \Drupal::request()->query->get('q');

    // $string = 'avvkscdcdc';
    $matches = array();
    if ($string) {
      $data = [];
      $connection = \Drupal\Core\Database\Database::getConnection();
      $query = $connection->select('scf_hot_topics_ordering', 'r');
      $query->join('node', 'n', 'r.nid = n.nid');
      $query->addField('r', 'nid', 'id');
      $result = $query->execute()->fetchAll();
      // dd( $result);
      foreach ($result as $key => $item) {
          $data[] = $item->id;
      }
      $query = $connection->select('node_field_data', 'n');
    //   dd( $query);
      $query->fields('n', ['nid', 'title']);
      $query->condition('n.type', ['resource','news_and_articles'], 'IN');
      $query->condition('n.status', 1);
  
      $query->condition('title', $connection->escapeLike($string).'%', 'LIKE');
      // dd( $query);

            //   ->condition('title', db_like($string) . '%', 'LIKE');
      
    //   $items = $query->execute();
      $items = $query->execute()->fetchAll();

      // dd($items);





      $sort_array = array();
      foreach ($items as $item) {
        $title = is_object($item) ? $item->title : '';
        
        $sort_array[$title . ' '.'  [nid:' . $item->nid . ']'] = $title ;
      }
    //   $title . ' ' .' [ '.'id:'.$item->id.' ] ' : '',
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







      

    //   foreach ($items as $item) {
    //     if(!in_array($item->nid, $data)) {
    //       $matches[$item->title.' [nid:'.$item->nid.']'] = check_plain($item->title);
    //     }
    //   }
    // }
    // drupal_json_output($matches);
  }

/**
 * remove_item_from_hot_topics
 * @param  integer $nid node id
 */
function remove_item_from_hot_topics($nid = 0) {
  // \Drupal::database()->selecte('scf_hot_topics_ordering')
  //   ->condition('nid', $nid)
  //   ->execute();


    $query = \Drupal::database()->delete('scf_hot_topics_ordering');
    $query->condition('nid', $nid);
    $query->execute();


    $tempstore = \Drupal::service('tempstore.private')->get('hot_topics');
    $tempstore->set('num_rows_hot_topics', $tempstore->get('num_rows_hot_topics') - 1);

    $redirect =new RedirectResponse('/admin/managing/manage-resource-order/hot-topics');
    $redirect->send();

    // drupal_goto(drupal_get_destination());
  }
  


  }












