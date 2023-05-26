<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;
use Drupal\Core\Database\Database;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Component\Serialization;
use Symfony\Component\Serializer\Encoder;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Url;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "hop_topics_resource",
 *   label = @Translation("Hop Topics Resources  API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/hop-topics-resources"
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




      $response = $this->getTopicsResources();
      $meta = ['info_text'=> 'Hot topics/resources'];

       $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  
      $results = new ResourceResponse($data);
       return $results;
      // return jsonSuccess($response, $meta);
  }


/**
   * get hot topics/resources
   * @param  Object $request
   * @return Bool
   */
  public function getTopicsResources()
  { 
    $items = [];
      $items['hot_topics'] = $this->getHotTopics();
      $items['top_resources'] = $this->getTopResources();
     return $items;
  }


  public function getHotTopics()
  {
 

    $items = $data = [];  


    $items = \Drupal::database()->select('scf_home_page_hot_topics_ordering' , 'h');
    $items->leftJoin('taxonomy_term_field_data' , 't', 't.  tid = h.tid');
    $items->condition('h.enabled', 1);
      // $items ->condition('h.status', 1);
      $items->orderBy('h.weight', 'asc');
      $items->fields('t', ['tid','name',]);
      $items->fields('h', ['id','title','link']);
      $results = $items->execute()->fetchAll();

      foreach($results as $item) {
        // dd($item);
        $link = '';
        $parent_tid = \Drupal::database()->select('taxonomy_term__parent', 'h')
        // ->condition('h.tid', $item->tid)
        ->condition('h.entity_id', $item->entity_id)
       
        ->range(0, 1)
        ->fields('h', ['parent_target_id']);
      
        $top_parent=[247,211,219];
        // dd($top_parent);
        if($parent_tid && $parent_tid > 0 && !in_array($parent_tid, $top_parent)) {
          $link = '/Home?journey=lead&topic_id='.$parent_tid.'&sub_topic_id='.$item->entity_id;
        } else {
          $link = '/Home?journey=lead&topic_id='.$item->entity_id;
        }

        if($item->entity_id == 63) {
          $link = '/strategy-method?journey=lead&topic_id='.$item->entity_id;
        }

        if(!empty($item->link) && $item->entity_id == 0) {
          $link = $item->link;
        }
     
      // $link = '';
      // switch ($item->tid) {
      //   case '259':
      //     $link = '/Home?journey=lead&topic_id=6680&sub_topic_id='.$item->tid;
      //     break;
      //   case '165':
      //     $link = '/Home?journey=lead&topic_id=59&sub_topic_id='.$item->tid;
      //     break;
      //   case '285':
      //     $link = '/Home?journey=lead&topic_id=101&sub_topic_id='.$item->tid;
      //     break;
      //   default:
      //   $link = '/Home?journey=lead&topic_id='.$item->tid;
      //     break;
      // }
        
        $data[] = ['title' => $item->title, 
        'id'=> $item->id.'-top-resource', 
        'link' => $link
        ];
      }
// dd($data);
     return $data;
  }

  public function getTopResources()
  {   
    
    

    $query = \Drupal::database()->select('scf_home_page_top_resources_ordering' , 'h');
    $query->condition('h.enabled', 1);
    $query ->orderBy('h.weight', 'asc');
     $query->fields('h' , [ 
     'title',
     'link', 
   
     ]);
   $result = $query->execute()->fetchAll();

   $results = [];
   foreach($result as $item) {

  $results[] = array(
 
  'title'=> $item->title,
  'link'=> $item->link,
    );
     }  
return $results;
  }

  } 
?>