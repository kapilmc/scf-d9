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
 *   id = "industryapi_resource",
 *   label = @Translation("industry API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-terms/industry"
 *   }
 * )
 */


class IndustryApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {




    $vid = 'industry';

    // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,1,TRUE);
    //  dd($terms);
  
    $result = [];
  foreach ($terms as $term) {
  
    
      $result[] = array(
       'id'=> $term->tid->value,
      //  'revision_id'=>$term->revision_id,
      //  'vid'=>$term->vid,
      //  'uuid'=>$term->uuid,
      //  'langcode'=>$term->langcode,
      //  'revision_user'=>$term->revision_user,
      //  'revision_created'=>$term->revision_created,
      //  'revision_log_message'=>$term->revision_log_message,
      //  'revision_default'=>$term->revision_default,
      //  'isDefaultRevision'=>$term->isDefaultRevision,
      //  'status'=>$term->status,
       'title'=> $term->name->value,
      //  'factiva_id'=>$term->factiva_id->value,
      //  'description'=>$term->description,
      //  'changed'=>$term->changed,
      //  'default_langcode'=>$term->default_langcode,
      //  'revision_translation_affected'=>$term->revision_translation_affected,
      //  'weight'=>$term->weight,
      //  'parent'=>$term->parent,
      //  'field_industry_links'=>$term->field_industry_links,
      //  'field_industry_text'=>$term->field_industry_text,
      //  'field_industry_url'=>$term->field_industry_url,
      //  'parents'=>$term->parents,

      
  
      ) ;
  }
  
  
  
  $term=['terms'=>$result];
  $meta = ['info_text'=>'Terms'];

$data = [ 'status'=>"success", 'data'=>$term, 'meta'=>$meta];
  
  
  
  
  
  
  // dd($result);
      $response = new ResourceResponse($data);
      $response->addCacheableDependency($data);
      return $response;
    
  
  
  
  
  









    //  $response = ['message' => 'Hello, this is a rest service and industry Api  to calls'];


    // return new ResourceResponse($response);
  
  }


}

?>