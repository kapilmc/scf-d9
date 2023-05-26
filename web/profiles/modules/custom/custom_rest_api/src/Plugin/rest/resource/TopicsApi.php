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
 *   id = "podcastapi_resource",
 *   label = @Translation("Topics API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-topics"
 *   }
 * )
 */


class TopicsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {




    $vid = 'topics';

  // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByproperties([
  //   'vid'=>$vid,
  //   // 'nmame'=>$term_namne,
  // ]);
  
  // dd($terms);
  
  // ->loadTree($vid);



  // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  // dd($terms);
  $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,5,TRUE);
    // dd($terms[1]->name->value);
// dd($terms);

  $result = [];

foreach ($terms as $term) {
  // // $data[] = array(
  // $lead[$term['title']] = $this->getTerms($term['id'], $topics_vid);
          

  // $meta = ['info_text'=>'Topics vocabulary'];

  if($term->name->value=='Corporate Finance'){
    $Finance[] = array(
     'id'=> $term->tid->value,
     'title'=> $term->name->value,
     'tooltip'=>$term->field_tool_tip->value,
    //  'description__value'=>$term->description__value,
    //  'description__format'=>$term->description__format,
    'revision_id'=>$term->revision_id->value,
     'url'=>$term->field_external_url->value,
    //  'active'=>$term->status,
    //  'contact' => $term_obj->get('externan_url')->value,
    // 'image_url' => $url,
    //  'changed'=>$term->changed

    ) ;
  }
  else if($term->name->value=='Stakeholders'){
    $Stakeholder[] = array(
     'id'=> $term->tid->value,
     'title'=> $term->name->value,
     'tooltip'=>$term->field_tool_tip->value,
    //  'description__value'=>$term->description__value,
    //  'description__format'=>$term->description__format,
    'revision_id'=>$term->revision_id->value,
     'url'=>$term->field_external_url->value,
    //  'active'=>$term->status->value,
    //  'contact' => $term_obj->get('externan_url')->value,
    // 'image_url' => $url,
    //  'changed'=>$term->changed
    

    ) ;
  }
  else if($term->name->value=='Strategy'){
    $Strategy[] = array(
     'id'=> $term->tid->value,
     'title'=> $term->name->value,
     'tooltip'=>$term->field_tool_tip->value,
    //  'description__value'=>$term->description__value,
    //  'description__format'=>$term->description__format,
    'revision_id'=>$term->revision_id->value,
     'url'=>$term->field_external_url->value,
    //  'active'=>$term->status->value,
    //  'contact' => $term_obj->get('externan_url')->value,
    // 'image_url' => $url,
    //  'changed'=>$term->changed

    ) ;
  }
}
$leads=['Corporate Finance'=>$Finance,'Stakeholders'=>$Stakeholder,'Strategy'=>$Strategy];
$wins=['Corporate Finance'=>$Finance,'Stakeholders'=>$Stakeholder,'Strategy'=>$Strategy];
$inspirs=['Corporate Finance'=>$Finance,'Stakeholders'=>$Stakeholder,'Strategy'=>$Strategy];

// $helo1=['inspire'=>$demo1];
// $helo2=['lead'=>$demo2];
// $helo3=['win'=>$demo3];

// dd($helo3);




$lead=['lead'=>$leads,'win'=>$wins,'inspire'=>$inspirs];



// $lead=['lead'=>$Finance,'win'=>$Stakeholder,'inspire'=>$Strategy];

// $win=['win'=>$Stakeholder];


// $inspire=['inspire'=>$Strategy,'Corporate Finance'=>'helo'];

// $inspire=['inspire'=>$demo];

$meta = ['info_text'=>'Topics vocabulary'];

$data = [ 'status'=>"success",  
'data' =>$lead, 
//  'win' => $win,
  // 'inspire' =>$inspir,
  'meta'=>$meta
];

//  $data=['data'=>$data,'meta'=>$meta];
 


// dd($result);

    $response = new ResourceResponse($data);
//  $response->addCacheableDependency($result,$meta);

    // $response = new ResourceResponse($meta);

    return $response;
  










  
  }

}

?>