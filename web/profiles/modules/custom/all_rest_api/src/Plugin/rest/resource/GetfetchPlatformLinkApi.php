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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;



/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "GetfetchPlatformLinkApi",
 *   label = @Translation("Get fetchPlatform Link Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-platform-link"
 *   }
 * )
 */


class GetfetchPlatformLinkApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    $response = $this->fetchPlatformLink();
    // dd($response);  


    $meta = ['info_text'=>'Economic Platform Analytics Link'];

    $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  
    

       $results = new ResourceResponse($data);
         $results->addCacheableDependency($data);
        return $results;

    //  return jsonSuccess($response, $meta);


      }





  public function fetchPlatformLink() {
    $response = [];
    $link = \Drupal::database()->select('variable', 'v');
    $link->condition('name','csvimporter-economic-analytic-link');
    $link ->distinct();
    $link->fields('v', ['value']);
    $results = $link->execute()->fetchAll();
   //  $link->fields( 'v',['value'])->execute();
   // ->get(array('value as value'));
    foreach($results as $key => $node) {


      $pos = strpos($node->value,'"');
     $substring = substr($node->value, $pos+1);
      $response[$key]['link'] = substr($substring,0,-2);

      
     }
    return $response;
   }

  }









 

?>