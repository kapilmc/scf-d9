<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "hoptopics_resource",
 *   label = @Translation("Hop Topics  API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/hop-topics-resources"
 *   }
 * )
 */


class HopTopicsResourcesApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    //  $response = ['message' => 'Hello, hop-topics-resources api'];


    // return new ResourceResponse($response);




  //   $items = [];
  //   $items['hot_topics'] = $this->getHotTopics();
  //   $items['top_resources'] = $this->getTopResources();
  //   dd($items);
  //  return $items;



// public function getHotTopics()
// {

  // $items = $data = [];  
  $query = \Drupal::database()->select('scf_home_page_hot_topics_ordering' , 'h');

// $query->leftjoin('taxonomy_term_data as t', 't.tid', '=', 'h.tid');


// $query->where('h.enabled', 1);
$query->orderBy('h.weight', 'asc');
//  $query->orderBy('h.weight', 'asc');

 $query->fields('h' ,  [
              
  't.tid',
  't/.name',
  'h.title',
  'h.id',
  'h.link',
]);


$result = $query->execute()->fetchAll();

    // dd($result);


    foreach($query as $item) {

      // dd($item);
      $link = '';
      // $parent_tid = DB::table('taxonomy_term_hierarchy as h')
      $parent_tid =  \Drupal::database()->select('taxonomy_term_hierarchy' , 'h')
      ->where('h.tid', $item->tid)
      // dd($item->tid);
      ->limit(1)
      ->value('h.parent');
      
      $top_parent=[247,211,219];
      if($parent_tid && $parent_tid > 0 && !in_array($parent_tid, $top_parent)) {
        $link = '/Home?journey=lead&topic_id='.$parent_tid.'&sub_topic_id='.$item->tid;
      } else {
        $link = '/Home?journey=lead&topic_id='.$item->tid;
      }

      if($item->tid == 63) {
        $link = '/strategy-method?journey=lead&topic_id='.$item->tid;
      }

      if(!empty($item->link) && $item->tid == 0) {
        $link = $item->link;
      }
   
   
      
      $data[] = ['title' => $item->title, 
      'id'=> $item->id.'-top-resource', 
      'link' => $link
      ];
    }

   return $data;
// }

// public function getTopResources()
// {    
  $items = [];
    // $items = DB::table("scf_home_page_top_resources_ordering as h")
    $items =  \Drupal::database()->select('scf_home_page_top_resources_ordering' , 'h')
    ->where('h.enabled', 1)
    ->orderBy('h.weight', 'asc')
    ->get(['h.title', 'h.link']);

   return $items;



















$meta = ['info_text'=>'Hot topics/resources'];
     
$data = [ 'status'=>"success", 'data'=>'Hot topics/resources', 'meta'=>$meta];
  
  
  
  
  
  
  // dd($result);
      $response = new ResourceResponse($data);
      $response->addCacheableDependency($data);
      return $response;
    
  
  
  









  
  }

  }


?>