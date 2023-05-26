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
 *   id = "feedbackdisplayapi_resource",
 *   label = @Translation("Feedback Display API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/feedback-display"
 *   }
 * )
 */


class FeedbackDisplayApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {



    //  $response = ['message' => 'feebackdisplay api '];


    // return new ResourceResponse($response);



    
    // $term=['data'=>'false'];
    // $meta = ['Display Feedback'];

  
  $data = [ 'status'=>"success", 'data'=>'false' , 'meta'=>'Display Feedback'];
    
  //  dd($data);
    
    
    
    // dd($result);
        $response = new ResourceResponse($data);
        $response->addCacheableDependency($data);
        return $response;
      
    
    





  }

}

?>