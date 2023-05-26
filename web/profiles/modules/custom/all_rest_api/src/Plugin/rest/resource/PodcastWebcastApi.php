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
 *   id = "Podcast Webcast Api",
 *   label = @Translation("Podcast Webcast Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-podcasts"
 *   }
 * )
 */
class PodcastWebcastApi extends ResourceBase {

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
        // dd('xmjsmjxdn');
       

      // $response = $this->getData();
      // $response = $this->getNodeDetails();




    //   $meta = ['info_text' => 'Get Help Block'];
      $result = [ 'status'=>"success", 'meta'=>$response];
        $results = new ResourceResponse($result); 
        
      return $results;

    }




    public function getData() {
      dd('xmsancxm,');

    
      $config = \Drupal::config('scfp_podcast_webcast_external.settings');



    $meta = ['info_text'=>'Podcast & Webcasts'];
    $ext_url = [
      'external_url' => [
        'podcasts' => [
          'title' => $config->get('scfp_podcast_external_link_title'),
          'url' => $config->get('scfp_podcast_external_link'),
        ],
        'webcasts' => [
          'title' => $config->get('scfp_webcast_external_link_title'),
          'url' => $config->get('scfp_webcast_external_link'),
        ],
      ],
      'webinar' => [
        'title' => $config->get('scf_webinars_title'),
        'nid' => $config->get('scf_webinars_nid'),
      ]
    ];
    $data = $meta + $ext_url;

    // return $data;

    // return jsonSuccess($response, $meta);











        // dd('cnmsn');


        // $response1 = $this->getPodcasts();
        $response2 = $this->getWebcasts();
        $response = array_merge($response1,$response2);
        return  $response;
      }
    
      public function getPodcasts() {

        dd('no database match in file ');
        // $podcast_uuid = '2b44656a-70cf-479a-9953-10c8da807853';
        // $tid = $this->getTermTidFromUUID($podcast_uuid);

        $nodes = \Drupal::database()->select('scf_webcasts_ordering' , 'b');
        $nodes->join('node_field_data', 'n', 'b.nid = n.nid');
        $nodes->join('field_data_field_is_webcasts_podcasts', 'p', 'n.nid = p.entity_id');
        // $nodes->leftJoin('field_data_field_date_of_the_podcast_webcas' , 'd', 'n.nid = d.entity_id');
        $nodes->condition('p.field_is_webcasts_podcasts_value', 1);
        $nodes->condition('n.type', 'resource');
        // $nodes->where('n.status', self::PUBLISHED);
        // $nodes->where('b.webcast_tid', $tid);
        $nodes ->orderBy('d.field_date_of_the_podcast_webcas_value', 'desc');
        $nodes->orderBy('n.created', 'desc');
        // dd($nodes);


        $nodes ->fields('n', ['nid']);
      $results =  $nodes->execute()->fetchAll();
      dd($results);
        //   ->get(['b.nid']);
    
        $result = [];
        foreach($nodes as $key => $node) {
          $result[] = $this->getNodeDetails($node->nid);
        }
        $response = ['podcasts'=>$result];
    
        return $response;
      }
      // public function getWebcasts() {

      //   dd('second page');
      //   $webcast_uuid = 'b0bc56ea-12da-42d3-81b6-a3e5a815189e';
      //   $tid = $this->getTermTidFromUUID($webcast_uuid);
    
      //   $nodes = \Drupal::database()->select('scf_webcasts_ordering', 'b')
      //     ->join('node' ,'n', 'b.nid = n.nid')
      //     ->join('field_data_field_is_webcasts_podcasts','p', 'n.nid = p.entity_id')
      //     ->leftJoin('field_data_field_date_of_the_podcast_webcas' , 'd', 'n.nid = d.entity_id')
      //     ->where('p.field_is_webcasts_podcasts_value', 1)
      //     ->where('n.type', 'resource')
      //     ->where('n.status', self::PUBLISHED)
      //     ->where('b.webcast_tid', $tid)
      //     ->orderBy('d.field_date_of_the_podcast_webcas_value', 'desc')
      //     ->orderBy('n.created', 'desc')

      //     ->fields('n', ['nid','type']);

      //     // ->get(['b.nid', 'n.type']);
    
      //   $result = [];
      //   foreach($nodes as $key => $node) {
      //     $result[] = $this->getNodeDetails($node->nid);
      //   }
      //   $response = ['webcasts' =>$result];
      //   return $response;
      // }
      // // public function getNodeDetails($nid) {
      //   public function getNodeDetails() {
      //     dd('node test');
      //   $node = \Drupal::database()->select('node' , 'n')
      //     ->where('n.nid', $nid)
      //     ->where('n.status', self::PUBLISHED)
      //     ->LeftJoin('field_data_field_date_of_the_podcast_webcas', 'b', 'n.nid = b.entity_id')
      //     ->LeftJoin('field_data_field_is_external_pw'  'e', 'n.nid = e.entity_id')
      //     ->LeftJoin('field_data_field_hosted_link_pw' , 'h', 'n.nid = h.entity_id')
      //     ->LeftJoin('field_data_field_host_name', 'ho', 'n.nid = ho.entity_id')
      //     ->LeftJoin('field_data_field_audio_video_upload' , 'v', 'n.nid = v.entity_id');
      //     dd($node);


      //     ->fields('n', ['nid','title']);


      //     // ->select(array('n.nid as nid',
      //     //   'n.title as title',
      //     //   'field_date_of_the_podcast_webcas_value as date',
      //     //   'field_is_external_pw_value as is_external',
      //     //   'field_hosted_link_pw_url as url',
      //     //   'field_audio_video_upload_fid as fid',
      //     //   'field_host_name_value as hostname'
      //     // ))
      //     // ->get();
      //   if(!empty($node[0])) {
      //     $data['nid'] = $node[0]->nid;
      //     $data['title'] = $node[0]->title;
      //     $data['date'] = date('F d, Y', strtotime($node[0]->date));
      //     $data['link'] = '';
      //     $data['type'] = '';
      //     $data['hostname'] = $node[0]->hostname ? $node[0]->hostname : '';
      //     if($node[0]->is_external === 1) {
      //       $data['link'] = $node[0]->url;
      //     }
      //     else {
      //       $data['link'] = $this->getFilepathFromFid($node[0]->fid);
      //     }
      //     if($node[0]->is_external === 1) {
      //       $data['type'] = 'link';
      //     }
      //     else {
      //       $data['type'] = $this->getFiletypeFromFid($node[0]->fid);
      //     }
      //   }
      //   return $data;
      // }
    









}




