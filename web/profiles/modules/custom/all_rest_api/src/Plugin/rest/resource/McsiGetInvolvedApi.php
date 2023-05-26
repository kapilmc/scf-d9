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
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\file\Entity\File;

/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "Mcsi Get Involved Api",
 *   label = @Translation("Mcsi Get Involved Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/mcsi-how-to-get-involved"
 *   }
 * )
 */
class McsiGetInvolvedApi extends ResourceBase {


  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
        $config = \Drupal::config('mcsi_get_involved.settings');
   

  //  $response[];
   $response = $this->getMcsiGetInvolved();

   $meta = ['info_text'=> $config->get('mcsi_how_to_get_involved_title') ? $config->get('mcsi_how_to_get_involved_title') : ''];

    $result =[ 'status'=>"success",  'data'=> $response,'meta'=>$meta];
   $results = new ResourceResponse($result);
   $results->addCacheableDependency($result);
    return $results;

   

    }






public function getMcsiGetInvolved()
    {
        $config = \Drupal::config('mcsi_get_involved.settings');

        $response = [];
        $overview =  $config->get('mcsi_how_to_get_involved');
        $response['title'] = $config->get('mcsi_how_to_get_involved_title') ? $config->get('mcsi_how_to_get_involved_title') : '';
        $response['text'] = isset($overview['value']) ? $overview['value'] : '';
        return  $response;
    }
















  }

