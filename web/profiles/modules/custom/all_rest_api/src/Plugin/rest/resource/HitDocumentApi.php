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
/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "hit page documents",
 *   label = @Translation("hit page documents"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/hit-page-documents"
 *   }
 * )
 */
class HitDocumentApi extends ResourceBase {


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
    $instance->logger = $container->get('logger.factory')->get('HitDocumentApi');
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



    // $response = ['message' => 'Hello, this is a rest service'];
    // return new ResourceResponse($response);


    $config = \Drupal::config('hit_page_sample_hits.settings');
  
    $response = [];
    $response['title'] = $config->get('hit_page_sample_hits_title') ? $config->get('hit_page_sample_hits_title') : '';

    $count = $config->get('hit_page_sample_items') ? $config->get('hit_page_sample_items') : 0;

    $items = [];
    for ($i = 0; $i < $count; $i++) {
      $items[] = ['title' =>  $config->get('hit_page_sample_hits_item_title' . $i), 'url' => $config->get('hit_page_sample_hits_item_descrition' . $i)];
    }
    $response['items'] = $items;
  
  $meta = ['info_text' => $config->get('hit_page_sample_hits_title') ? $config->get('hit_page_sample_hits_title') : ''];

// $meta = 'hit page OverView';
  
//  $result = ['success'=> $response ,'meta_text'=>$meta ];
        // return new JsonResponse($result);
    // }
    $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];

    $response = new ResourceResponse($result);
    $response->addCacheableDependency($result);
     return $response;

     
  }

 

  }

