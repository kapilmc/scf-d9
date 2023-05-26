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
 *   id = "podcastsapi_resource",
 *   label = @Translation("podcasts API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/podcasts"
 *   }
 * )
 */


class PodcastsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {


//     $query = \Drupal::database()->select('variable', 't');
// $query->fields('t', ['name','value']);
// $result = $query->execute()->fetchAll();
// dd($result);


     $response = ['message' => 'Hello, this is a rest service and podcast   Api  to calls'];


    return new ResourceResponse($response);
  
  }

}

?>