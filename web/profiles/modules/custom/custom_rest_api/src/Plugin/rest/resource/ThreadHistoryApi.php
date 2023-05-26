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
use Drupal\Core\Database\Database;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "threadapi_resource",
 *   label = @Translation("Thead API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/thead-history"
 *   }
 * )
 */


class ThreadHistoryApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {



//  $response = ['data' => 'Hello, topics wefknfkejwn demo api',
// 'hello'=>',nfksne','demo'=>'hello demo'];
// $meta = ['info_text'=>'Topics vocabulary'];

//     return new ResourceResponse($response );


$data = [ 'messages'=>[]];
    
  //  dd($data);
    
    
    
    // dd($result);
        $response = new ResourceResponse($data);
        $response->addCacheableDependency($data);
        return $response;
      

  }
}

