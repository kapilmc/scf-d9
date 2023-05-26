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
 *   id = "strategy_resource",
 *   label = @Translation("Strategy_Area API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-terms/strategy_area"
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
      // 'image_url' => $url,
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