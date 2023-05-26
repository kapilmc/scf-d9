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
 *   id = "Request A Hit Api",
 *   label = @Translation("Request A Hit Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/hit-page-request-a-hit"
 *   }
 * )
 */
class RequestAHitApi extends ResourceBase {


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
    $instance->logger = $container->get('logger.factory')->get('RequestAHitApi');
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

      $response['requirements'] = $this->hit_page_request();
      $response['journey_map'] = $this->hit_page_journey();

      $meta = ['info_text' => 'Request a HIT'];
      $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
        $results = new ResourceResponse($result);
        //  return  jsonsuccess($results);
      return $results;



  

    }



    public function  hit_page_request(){

 

    $config = \Drupal::config('hit_page_request.settings');
 

  $response = [];
  $hit_insight_descrition_1 =  $config->get('request_a_hit_descrition_1');
  $hit_insight_descrition_2 = $config->get('request_a_hit_descrition_2');
  $hit_insight_descrition_3 = $config->get('request_a_hit_descrition_3');
  $response['title'] = $config->get('request_a_hit_title') ? $config->get('request_a_hit_title') : '';
  $response['footer_text'] = $config->get('request_a_hit_footer_text') ? $config->get('request_a_hit_footer_text') : '';
  $response['item'][0]['title'] = $config->get('request_a_hit_title_1') ? $config->get('request_a_hit_title_1') : '';
  $response['item'][0]['text'] = isset($hit_insight_descrition_1['value']) ? $hit_insight_descrition_1['value'] : '';
  $response['item'][1]['title'] = $config->get('request_a_hit_title_2') ? $config->get('request_a_hit_title_2') : '';
  $response['item'][1]['text'] = isset($hit_insight_descrition_2['value']) ? $hit_insight_descrition_2['value'] : '';
  $response['item'][2]['title'] = $config->get('request_a_hit_title_3') ? $config->get('request_a_hit_title_3') : '';
  $response['item'][2]['text'] = isset($hit_insight_descrition_3['value']) ? $hit_insight_descrition_3['value'] : '';

return $response;

$meta = ['info_text' => 'Request a HIT'];
$result =['success'=>$response,'mete_text'=>$meta];

  $results = new ResourceResponse($result);

//    return  jsonsuccess($results, $meta);
return $results;

     
  }



  public function hit_page_journey (){
    $config = \Drupal::config('hit_page_journey.settings');

$config = \Drupal::config('hit_page_journey.settings');

$response = [];
    $fid = $config->get('hit_page_journey_file');
    $file = File::load($fid[0]);
    // $file_relative_url = $file->createFileUrl();
   $path = $file->createFileUrl(FALSE);
 
    $response['title'] = $config->get('hit_page_journey_title') ? $config->get('hit_page_journey_title') : '';
    // $response['image'] = $fid ->getFileUri();
    // $response['image'] = (isset($fid) && $fid > 0) ? $this->getFilepathFromFid($fid) : '';
    // $response['image'] = $config->get('hit_page_journey_file') ? $config->get('hit_page_journey_file') : '';
    $response['image'] = $path;
    $response['link'] = $config->get('hit_page_journey_url') ? $config->get('hit_page_journey_url') : '';
    return  $response;
  }


}
