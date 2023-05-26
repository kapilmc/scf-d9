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
 *   id = "Get external links Api",
 *   label = @Translation("Get external links Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-external-links"
 *   }
 * )
 */


class GetexternallinksApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {
    $config = \Drupal::config('scfp_podcast_webcast_external.settings');
    $ext_url = [
      'external_url' => [
        'events' => [
          'title' => $config->get('scfp_events_external_link_title'),
          'url' => $config->get('scfp_events_external_link'),
        ],
      ],
    ];

    $meta = ['info_text'=>'External Links'];
    // $meta = $meta + $ext_url;

    $data = [ 'status'=>"success", 'data'=>$ext_url, 'meta'=>$meta];  
    
    $results = new ResourceResponse($data);
   
    //  return  jsonsuccess($results);
    return $results;

  }
  }

 

?>