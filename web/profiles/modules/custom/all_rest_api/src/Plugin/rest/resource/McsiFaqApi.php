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
 *   id = "Mcs iFaq Api",
 *   label = @Translation("Mcsi Faq Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/mcsi-faq"
 *   }
 * )
 */
class McsiFaqApi extends ResourceBase {


  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
      

        $config = \Drupal::config('mcsi_faq.settings');
   
   $response = $this->getMcsiFaq();
   $meta = ['info_text'=> $config->get('mcsi_faq_title') ? $config->get('mcsi_faq_title') : ''];

    $result =[ 'status'=>"success",  'data'=> $response,'meta'=>$meta];
   $results = new ResourceResponse($result);
   $results->addCacheableDependency($result);
    return $results;

   

    }


public function getMcsiProjects()
{
    $config = \Drupal::config('mcsi_projects.settings');
   
    $response = [];
    $response['title'] = $config->get('mcsi_projects_title') ? $config->get('mcsi_projects_title') : '';
    $data = [];
    $nodes = \Drupal::database()->select('scf_mcsi_projects_ordering', 'p');
    $nodes->join('node_field_data', 'n', 'n.nid = p.nid');
    $nodes ->leftJoin('field_data_body' , 'b', 'b.entity_id = n.nid');
    $nodes ->condition('n.type', 'mcsi_project');
    $nodes->condition('n.status', 1);
    $nodes ->orderBy('p.weight', 'asc');
        $nodes->range(0, 15);

        $nodes->fields('n', ['nid','title']);
        $nodes ->fields('b', ['body_value']);
        $results = $nodes->execute()->fetchAll();
       $data = [];
       foreach($results as $key => $node) {
         $data[] = array(
     
         'nid'=> $node->nid,
         'title'=> $node->title,
         'tex'=> $node->body_value,

        );
      
        }
 
    // return $data;

    $response['data'] = $data;
    return  $response;
}


public function getMcsiFaq(){
    $config = \Drupal::config('mcsi_faq.settings');
    $response = [];
    $response['title'] = $config->get('mcsi_faq_title') ? $config->get('mcsi_faq_title') : '';
    $data = [];
    $nodes = \Drupal::database()->select('scf_mcsi_faq_ordering' ,'p');
    $nodes->join('node_field_data' , 'n', 'n.nid = p.nid');
    $nodes->leftJoin('field_data_body', 'b', 'b.entity_id = n.nid');
    $nodes->condition('n.type', 'faq');
    $nodes->condition('n.status', 1);
    $nodes->orderBy('p.weight', 'asc');
    $nodes->range(0, 15);

        $nodes->fields('n', ['nid','title']);
        $nodes ->fields('b', ['body_value']);
        $results = $nodes->execute()->fetchAll();

        // ->get(array(
        //     'n.nid as nid', 'n.title as title',
        //     'b.body_value as text',
        // ));

        $data = [];
        foreach($results as $key => $node) {
          $data[] = array(
      
          'nid'=> $node->nid,
          'title'=> $node->title,
          'tex'=> $node->body_value,
 
         );
       
         }


    $response['data'] = $data;
    return  $response;
}

  }

