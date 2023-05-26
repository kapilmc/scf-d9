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
 *   id = "CountriesRegionApi",
 *   label = @Translation("Countries Region Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-regions"
 *   }
 * )
 */


class CountriesRegionApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    $response = '';
    $response = $this->getRegionData();
  

    $meta = ['info_text'=>'Economic Indicators Regions'];

    $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];

       $results = new ResourceResponse($result);
        return $results;

    
}


public function getRegionData() {
    $response = [];
    $countries = \Drupal::database()->select('csvImporter', 'csv')
      ->condition('is_country','=' ,0)
      ->distinct()

   ->fields('csv',['country'])->execute()->fetchall();
    foreach($countries as $key=> $node) {
      $response[$key]['country'] = $node->country;
    }
    return $response;
  }


 

}


?>