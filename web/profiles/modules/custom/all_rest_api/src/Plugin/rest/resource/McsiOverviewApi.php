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
use Drupal\file\Entity\File;
// use Illuminate\Support\Facades\DB;
/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "Mcsi Overview Api",
 *   label = @Translation("Mcsi Overview Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/mcsi-overview"
 *   }
 * )
 */
class McsiOverviewApi extends ResourceBase {


 /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    $instance = parent::create($container, 
      $configuration, 
      $plugin_id, 
      $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('Mcsi Overview Api');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

    /**
     * Responds to GET requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */

    public function get() {
      $config = \Drupal::config('mcsi_overview.settings');

  
   $response['Overview'] = $this->mcsiOverview();


  
   $meta = ['info_text'=> $config->get('mcsi_overview_title') ? $config->get('mcsi_overview_title') : ''];

   $result =[ 'status'=>"success",  'data'=> $response,'meta'=>$meta];

   $results = new ResourceResponse($result);
   $results->addCacheableDependency($result);
    return $results;

    // // return jsonSuccess($results, $meta);



    }


public function mcsiOverview(){

  $config = \Drupal::config('mcsi_overview.settings');

  $response = [];
  $overview =  $config->get('mcsi_overview');
  $response['title'] = $config->get('mcsi_overview_title') ? $config->get('mcsi_overview_title') : '';
  $response['text'] = isset($overview['value']) ? $overview['value'] : '';
  $fid = $config->get('mcsi_video_image');
        $file = File::load($fid[0]);
        // $file_relative_url = $file->createFileUrl();
       $path = $file->createFileUrl(FALSE);
       $fid = $config->get('mcsi_video');
       $file = File::load($fid[0]);
       // $file_relative_url = $file->createFileUrl();
      $paths = $file->createFileUrl(FALSE);


    $response['thumb'] = $path;
    $response['video'] = $paths;


  return  $response;

}


  }

