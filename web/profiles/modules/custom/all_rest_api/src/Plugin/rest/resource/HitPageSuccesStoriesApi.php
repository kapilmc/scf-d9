<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
// use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;
use Drupal\Core\File\FileSystem;


/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "Succes Stories",
 *   label = @Translation("Succes Stories"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/hit-page-success-stories"
 *   }
 * )
 */


class HitPageSuccesStoriesApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {


    $response['quotes'] = $this->quotes();
    $response['video'] = $this->impact_ordering();
    $meta = ['info_text' => 'Success stories'];   
    $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
      $results = new ResourceResponse($data);
   
      //  return  jsonsuccess($results);
    return $results;



  }



    public function quotes(){

    $config = \Drupal::config('hit_page_quotes.settings');


        $response = [];
        $response['title'] = $config->get('hit_page_quotes_title') ? $config->get('hit_page_quotes_title') : '';
    
    
        $count = $config->get('hit_page_quotes_items') ? $config->get('hit_page_quotes_items') : 0;
    
        $items = [];
        for ($i = 0; $i < $count; $i++) {
          $overview = [];
          $overview = $config->get('hit_page_quotes_item_descrition_' . $i);
          $quote = isset($overview['value']) ? $overview['value'] : '';
          if ($quote) {
            $items[] = [
              'by' =>  $config->get('hit_page_quotes_item_title_' . $i),
              'sub_title' =>  $config->get('hit_page_sub_title_' . $i),
              'quote' =>  isset($overview['value']) ? $overview['value'] : ''
            ];
          }
        }
        $response['items'] = $items;
        return  $response;
        }

public function impact_ordering(){
    $response = [];
    $config = \Drupal::config('hit_page_impact.settings');
    $response['title'] = $config->get('hit_page_impact_title') ? $config->get('hit_page_impact_title') : '';
    $data = [];     
    $connection = \Drupal::database();
    $query = $connection->select('scf_hit_page_impact_ordering', 'p');
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
    $data = [];
    foreach ($results as  $node) {
     
      $fid =$node->field_video_thumbnail_target_id;
      $file = \Drupal\file\Entity\File::load($fid); 
      // $file_relative_url = $file->createFileUrl();
     $path = $file->createFileUrl(FALSE);
     
      // dd($path);

      $fid =$node->field_video_target_id;
      $file = \Drupal\file\Entity\File::load($fid); 
      // $file_relative_url = $file->createFileUrl();
     $paths = $file->createFileUrl(FALSE);
       $data[] = [
      'nid' => $node->nid,
      'title' => $node->title,
      'thumb' => $path,
      'video' => $paths, 
       ];
     }

    $response['data'] = $data;
    return  $response;

  }

}
