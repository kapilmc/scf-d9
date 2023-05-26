<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
// use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "get_content_rest_resource",
 *   label = @Translation("testing API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/testing"
 *   }
 * )
 */


class TestRestApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {



    //  $response = ['message' => 'Hello, this is a rest service and testing api '];


    // return new ResourceResponse($response);



  //   if (!$this->loggedUser->hasPermission('access content')) {
  //     throw new AccessDeniedHttpException();
  //   }	
  //   $entities = \Drupal::entityTypeManager()
  //   ->getStorage('taxonomy_term')
  //   ->loadMultiple();
  // foreach ($entities as $entity) {
  //   $result[$entity->id()] = $entity->title->value;
  // }




  // $vid = '7_building_block';
//   $vid = 'competitor';
//  $vid = 'expert_geography';
//  $vid = 'expert_geography_list';
 $vid = 'expert_industry_via_api';
//  $vid = 'expert_role';
//  $vid = 'first_practice_area';
//  $vid = 'free_keywords';
//  $vid = 'geography_filter';
//  $vid = 'industry';
//  $vid = 'industry_case_library';
//  $vid = ' inspire_your_ceo_cfo_dialogue';
//  $vid = 'keywords_for_search';
//  $vid = 'keywords_case_library';
//  $vid = 'navigate_our_practice';
//  $vid = 'news_source';
//  $vid = 'podcast_webcasts';
//  $vid = ' region_case_library';
//  $vid = 'resource_category';
//  $vid = 'resource_label';
//  $vid = 'resource_type';
//  $vid = 'continuous_learning';
//  $vid = 'service_line';
//  $vid = 'expert_category';
//  $vid = 'sl_sub_area';
//  $vid = 'strategy_area';
//  $vid = 'tags';
//  $vid = 'topics';
//  $vid = 'user_expert';
//  $vid = 'user_journey';
//  $vid = 'win_category';
 

// $query = \Drupal::entityQuery('taxonomy_term');
// $query->condition('vid',"topics");
// $tid=$query->excute();
// $terms=\Drupal\taxonomy\Entity\Term::loadMultiple($tid);
// dd($terms);







  $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  // dd($terms);




  $result = [];
foreach ($terms as $term) {

  // dd ($terms);
    // $tid = $term->tid;

    // $result[$term->tid()] = $term->name;
    // $tid = $term->tid;
  
    $result[] = array(
     'id'=> $term->tid,
     'name'=> $term->name,
    //  'description__value'=>$term->description__value,
    //  'description__format'=>$term->description__format,
    'revision_id'=>$term->revision_id,
     'external'=>$term->external

    ) ;
}

// dd($result);
    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
    return $response;
  


  
  }



  public function post(){

  }


  

}

?>