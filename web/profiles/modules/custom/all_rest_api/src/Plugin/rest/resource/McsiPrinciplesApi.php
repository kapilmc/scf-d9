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
 *   id = "Mcsi Principles Api",
 *   label = @Translation("Mcsi Principles Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/mcsi-principles"
 *   }
 * )
 */
class McsiPrinciplesApi extends ResourceBase {


  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
    

        $config = \Drupal::config('mcsi_principles.settings');
        // dd($config);

  //  $response[];
   $response = $this->getMcsiPrinciples();

   $meta = ['info_text'=> $config->get('mcsi_principles_title') ? $config->get('mcsi_principles_title') : ''];

    $result =[ 'status'=>"success",  'data'=> $response,'meta'=>$meta];
   $results = new ResourceResponse($result);
   $results->addCacheableDependency($result);
    return $results;

   

    }


public function getMcsiPrinciples()
{
    $config = \Drupal::config('mcsi_principles.settings');
    // dd('cdsklvfnfs');
    $response = [];
    // $overview =  $this->getAltVariable('mcsi_overview');
    $response['title'] = $config->get('mcsi_principles_title') ? $config->get('mcsi_principles_title') : '';
    $data = [];
    $nodes = \Drupal::database()->select('scf_mcsi_principles_ordering', 'p');
    $nodes->join('node_field_data', 'n', 'n.nid = p.nid');
    $nodes->leftJoin('field_data_body', 'b', 'b.entity_id = n.nid');
    $nodes->LeftJoin('node_revision__field_image' ,'im', 'p.nid = im.entity_id');
    $nodes->condition('n.type', 'principles');
    $nodes->condition('n.status', 1);
    $nodes->orderBy('p.weight', 'asc');
    $nodes->range(0, 15);
    $nodes->fields('n', ['nid','title']);
 
    $nodes ->fields('b', ['body_value']);
    $nodes ->fields('im', ['field_image_target_id']);
    $results = $nodes->execute()->fetchAll();
        // ->get(array(
        //     'n.nid as nid', 'n.title as title',
        //     'b.body_value as text', 'field_image_fid as image_src',
        // ));
        $data = [];
    foreach ($results as $node) {


     
       
    //     $fid = $node->field_image_target_id;
    //     // dd($fid);
    //     $file = File::load($fid[0]);
    //     // $file_relative_url = $file->createFileUrl();
    //    $path = $file->createFileUrl(FALSE);

   

    // dd($path);
     
        $data[] = array(

     
                     'nid'=> $node->nid,
                     'title'=> $node->title,
                     'tex'=> $node->body_value,
                     'image_src'=> $node->field_image_target_id,
                    //  'image_src'=> $path,
               
            
                    );



        // $image_path = ($node->image_src === null) ?   SITE_PATH . '/sites/all/themes/scfp/images/pdefault.png' : $this->getFilepathFromFid($node->image_src);
        // $node->image_src = $image_path;
        // $data[] = $node;
    }
    $response['data'] = $data;
    return  $response;
}





















  }

