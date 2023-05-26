<?php
 
/**
 * @file
 * @Contains Drupal\scfp_landing_page\Controller\ScfpController.
 */

namespace Drupal\scfp_landing_page\Controller;

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
class ScfpController extends ControllerBase {

 



/**
* autocomplete_item_from_hot_topics1
* @return JSON
*/
function autocomplete_item_from_home_page_hot_topics() {
  $string = \Drupal::request()->query->get('q');
  // dd($string);
  // $query = \Drupal::entityQuery('taxonomy_term');
  // $query->condition('vid', "topics");
  // $tids = $query->execute();
  // $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
  // dd($terms);
  // $string = 'm';
  if (!empty($string)) {
    $result = \Drupal::entityQuery('taxonomy_term')
    ->condition('name', '%' . $string . '%', 'LIKE')
    ->condition('vid', "topics")
    ->execute();
    

    $terms = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_term')
    ->loadMultiple($result); 
    foreach ($terms as $term) {
       $matches[] = ['value'=>$term->name->value.' [tid:'. $term->tid->value.']','label'=>$term->name->value];
    }

    return new JsonResponse($matches);
  }
  
   
}


/**
 * remove_item_from_home_page_news
 * @param  integer $id news id
 */
function remove_item_from_home_page_news($id = 0) {
    \Drupal::database()->delete('scf_home_page_news_ordering')
    ->condition('id', $id)
    ->execute();
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');
    $tempstore->set('num_rows_news_page', $tempstore->get('num_rows_news_page') - 1);  
    $redirect =new RedirectResponse('/admin/managing/home-page/news');
    $redirect->send();
  

  }
  



   /**
     * remove_item_from_home_page_top_resources
     * @param  integer $id top resource id
     */
function remove_item_top_resources($id = 0) {

    $query = \Drupal::database()->delete('scf_home_page_top_resources_ordering');
    $query->condition('id', $id);
    $query->execute();
    $redirect =new RedirectResponse('/admin/managing/home-page/top-resources');
    $redirect->send();
  
    
  }


/**
 * remove_item_from_home_page_hot_topics
 * @param  integer $tid topic id
 */
function remove_item_from_home_page_hot_topics($id = 0) {
  // dd($id);
  \Drupal::database()->delete('scf_home_page_hot_topics_ordering')
    ->condition('id', $id)
    ->execute();

    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');
    $tempstore->set('num_rows_scfp_landing_page', $tempstore->get('num_rows_scfp_landing_page') - 1);

  
  $redirect =new RedirectResponse('/admin/managing/home-page/hot-topics');
        $redirect->send();
}


/**
 * remove_item_from_home_page_hot_topics
 * @param  integer $tid topic id
 */
function remove_item_from_home_page_top_resources($id = 0) {
  \Drupal::database()->delete('scf_home_page_top_resources_ordering')
    ->condition('id', $id)
    ->execute();

  $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');
  $tempstore->set('num_rows_resources_page', $tempstore->get('num_rows_resources_page') - 1);  
  $redirect =new RedirectResponse('/admin/managing/home-page/top-resources');
        $redirect->send();
}


  


// theme --------------

//   public function homenewsordering() {

//     $items = array(
//       array('name' => 'Article one'),
//       array('name' => 'Article two'),
//       array('name' => 'Article three'),
//       array('name' => 'Article four'),
//     );

//     return array(
//       '#theme' => 'article_list',
//       '#items' => $items,
//       '#title' => 'Our article list'
//     );
//   }





}




