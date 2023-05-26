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
 *   id = "strategy resource",
 *   label = @Translation("Strategy Area API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-terms/strategy_area"
 *   }
 * )
 */


class StrategyAreaApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    


    $vid = 'strategy_area';

    // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,1,TRUE);
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,4,TRUE);
    //  dd($terms);
  
    $result = [];
  foreach ($terms as $term) {
  
    
      $result[] = array(
       'id'=> $term->tid->value,
       'title'=> $term->name->value,
      //  'description__value'=>$term->description__value,
      //  'description__format'=>$term->description__format,
      // 'revision_id'=>$term->revision_id,
    //    'external_url'=>$term->field_external_url,
      //  'status'=>$term->status,
      //  'contact' => $term_obj->get('externan_url')->value,
      // 'image_url' => $url->value,
      //  'changed'=>$term->changed
  
      ) ;
  }
  
  
  
  
  $term=['terms'=>$result];
  $meta = ['info_text'=>'Terms'];

$data = [ 'status'=>"success", 'data'=>$term, 'meta'=>$meta];
  
  
  
  
  
  // dd($result);
      $response = new ResourceResponse($data);
      $response->addCacheableDependency($data);
      return $response;
    










  }


}

?>