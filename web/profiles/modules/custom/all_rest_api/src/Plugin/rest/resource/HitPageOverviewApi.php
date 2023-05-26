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
// use Illuminate\Support\Facades\DB;
/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "hit page overview",
 *   label = @Translation("hit page overview"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/hit-page-what-is-a-hit"
 *   }
 * )
 */
class HitPageOverviewApi extends ResourceBase {


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
    $instance->logger = $container->get('logger.factory')->get('hit page overview');
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

  //  $response[];
   $response['Overview'] = $this->overview();
   $response['insights'] = $this->HitInsights();
   $response['video'] = $this->HitLeaders();
  
   $meta = ['info_text' => 'What is a HIT?'];

       
   $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];

// $result['data'] =['status'=>'success', $response,'meta'=>$meta];
   $results = new ResourceResponse($data);
   // $results->addCacheableDependency($result);
    return $results;

    // // return jsonSuccess($results, $meta);



    }


public function overview(){
  // dd('jsdfbjvdfk');


   
    $config = \Drupal::config('hit_page_overview.settings');
    $response = [];
    $overview =  $config->get('hit_page_overview');
    $response['title'] = $config->get('hit_page_overview_title') ? $config->get('hit_page_overview_title') : '';
    $response['footer_text'] = $config->get('hit_page_overview_footer_text') ? $config->get('hit_page_overview_footer_text') : '';
    $response['text'] = isset($overview['value']) ? $overview['value'] : '';

    return $response;

// dd($response);



//     $meta = ['info_text' => $config->get('hit_page_overview_title') ? $config->get('hit_page_overview_title') : ''];
  
//  $result = ['success'=> $response ,'meta'=>$meta ];
//         // return new JsonResponse($result);
//     // }

//   //  $results = new ResourceResponse($result);
//     // $results->addCacheableDependency($result);
//      return $result;

    //  return jsonSuccess($response, $meta);

     
  }





public function HitInsights(){
  // dd('sdnvjsnd');

  $config = \Drupal::config('hit_page_insights.settings');
  $response = [];
  $hit_insight_descrition_1 =  $config->get('hit_insight_descrition_1');
  $hit_insight_descrition_2 = $config->get('hit_insight_descrition_2');
  $hit_insight_descrition_3 = $config->get('hit_insight_descrition_3');
  $response['title'] = $config->get('hit_page_insight_title') ? $config->get('hit_page_insight_title') : '';
  $response['item'][0]['title'] = $config->get('hit_insight_title_1') ? $config->get('hit_insight_title_1') : '';
  $response['item'][0]['text'] = isset($hit_insight_descrition_1['value']) ? $hit_insight_descrition_1['value'] : '';
  $response['item'][1]['title'] = $config->get('hit_insight_title_2') ? $config->get('hit_insight_title_2') : '';
  $response['item'][1]['text'] = isset($hit_insight_descrition_2['value']) ? $hit_insight_descrition_2['value'] : '';
  $response['item'][2]['title'] = $config->get('hit_insight_title_3') ? $config->get('hit_insight_title_3') : '';
  $response['item'][2]['text'] = isset($hit_insight_descrition_3['value']) ? $hit_insight_descrition_3['value'] : '';
// dd($response);
return $response;


}




public function HitLeaders(){
  // dd('csd,mcjknbsj');
        $response = [];
        $config = \Drupal::config('hit_page_leadership.settings');

      $response['title'] = $config->get('hit_page_leadership_title') ? $config->get('hit_page_leadership_title') : '';

       $data = [];     


      $connection = \Drupal::database();
      $query = $connection->select('scf_hit_page_leadership_ordering', 'p');
      $query ->join('node_field_data', 'n', 'n.nid = p.nid');
      $query->leftJoin('node_revision__field_video_thumbnail' , 'b', 'b.entity_id = n.nid');
      $query->leftJoin('node_revision__field_video', 'v', 'v.entity_id = n.nid');
      $query->condition('n.type', 'hit_video');
      $query->condition('n.status', 1);
      $query->fields('n', ['nid']);
      $query->fields('n', ['title']);
      $query->fields('b', ['field_video_thumbnail_target_id']);
      $query->fields('v', ['field_video_target_id']);
      $query->orderBy('p.weight', 'asc');
      $query->range(0, 16);
      $results = $query->execute()->fetchAll();
      // dd($results);


      $data = [];
      foreach ($results as  $node) {
      
      
        $fid =$node->field_video_thumbnail_target_id;
        $file = \Drupal\file\Entity\File::load($fid); 
        // $file_relative_url = $file->createFileUrl();
       $path = $file->createFileUrl(FALSE);
       
   

        $fid =$node->field_video_target_id;
        $file = \Drupal\file\Entity\File::load($fid); 
        // $file_relative_url = $file->createFileUrl();
       $paths = $file->createFileUrl(FALSE);
       
   
    


         $data[] = [
        'nid' => $node->nid,
        'title' => $node->title,
        'thumb' => $path,
        'video' => $paths,
        // dd($node);

         ];


      }

// dd($data);

      $response['data'] = $data;
      // dd($response);
      return  $response;



}


 

  }

