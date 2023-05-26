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

use Drupal\Core\File\FileSystem;
use Drupal\Core\Url;

use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "Get Involved Api",
 *   label = @Translation("Get Involved Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-involved"
 *   }
 * )
 */
class GetInvolvedApi extends ResourceBase {

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
       

      $response = $this->getInvolveData();
      $meta = ['info_text' => 'Get Involved'];
      $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
        $results = new ResourceResponse($result); 
        //  return  jsonsuccess($results);
      return $results;

    }
    public function getInvolveData(){
        $config = \Drupal::config('scfp_get_involved.settings'); 
      $response = [];
      $matches=[];
      $nid= 0;
      $result = preg_match('/\[.*\:(\d+)\]$/', $config->get('get_involved_block1_nid'), $matches);
      $nid = isset($matches[$result]) ? $matches[$result] : 0;
      $response[] = [
        'id' => $config->get('get_involved_block1_id'),
        'title' => $config->get('get_involved_block1_title'),
        'nid' => $nid,
        'text' => $config->get('get_involved_block1_summary'),
        'external' => false,
        'url' => '',
      ];
  
      $matches=[];
      $nid= 0;
      $result = preg_match('/\[.*\:(\d+)\]$/', $config->get('get_involved_block2_nid'), $matches);
      $nid = isset($matches[$result]) ? $matches[$result] : 0;
      $response[] = [
        'id' => $config->get('get_involved_block2_id'),
        'title' => $config->get('get_involved_block2_title'),
        'nid' => $nid,
        'text' => $config->get('get_involved_block2_summary'),
        'external' => false,
        'url' => '',
      ];
  
      $response[] = [
        'id' => $config->get('get_involved_block3_id'),
        'title' => $config->get('get_involved_block3_title'),
        'nid' => 0,
        'text' => $config->get('get_involved_block3_summary')['value'],
        'external' => true,
        'url' => trim($config->get('get_involved_block3_url')),
      ];
  
      $matches=[];
      $nid= 0;
      $result = preg_match('/\[.*\:(\d+)\]$/', $config->get('get_involved_block4_nid'), $matches);
      $nid = isset($matches[$result]) ? $matches[$result] : 0;
      $response[] = [
        'id' => $config->get('get_involved_block4_id'),
        'title' => $config->get('get_involved_block4_title'),
        'nid' => $nid,
        'text' => $config->get('get_involved_block4_summary'),
        'external' => false,
        'url' => '',
      ];
  
      return $response;
    }

}




