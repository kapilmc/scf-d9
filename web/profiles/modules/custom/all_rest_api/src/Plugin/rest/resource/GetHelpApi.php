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
 *   id = "Get Help Api",
 *   label = @Translation("Get Help Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-help"
 *   }
 * )
 */
class GetHelpApi extends ResourceBase {

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
       

      $response = $this->fetchGetHelpBlock();
      $meta = ['info_text' => 'Get Help Block'];
      $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
        $results = new ResourceResponse($result); 
        //  return  jsonsuccess($results);
      return $results;

    }



    public function fetchGetHelpBlock(){
        $config = \Drupal::config('scfp_get_help.settings');

      $response = '';
      $title = $config->get('scfp_get_help_title') ? $config->get('scfp_get_help_title') : 'Get Help';

      $links =  \Drupal::database()->select('scfp_get_help_ordering', 'n');
      $links->orderBy('n.weight', 'asc');

      $links ->fields('n', ['id','link_title','link_url']);
      $results = $links->execute()->fetchAll();

        // ->select(['n.link_title as title','n.link_url as link'])
        // ->orderBy('n.weight', 'asc')
        // ->get();

        $data = [];
      foreach ($results as $key => $value) {
        // $data = array(
        //     $data[$ke]['title'] = $value->link_title,
        //     $data[$ke]['link'] = '<a href="mailto:'.$value->link_url.'">'.$value->link_title.'</a>',       
        // );

        $data[] = array(
            'link'=> $value->link_url,
            'title'=> $value->link_title,
           );

        
        // $results[$key]->title =$value->title;
        // $results[$key]->link = '<a href="mailto:'.$results[$key]->link.'">'.$value->title.'</a>';
        // dd($results);
      }
      $submit_title = $config->get('scfp_get_help_submit_title') ? $config->get('scfp_get_help_submit_title') : 'Submit a R&I Request';
      $submit_url = $config->get('scfp_get_help_submit_url') ? $config->get('scfp_get_help_submit_url') : 'http://home.intranet.mckinsey.com/research/kmsc/weberrf.nsf/Request?openform';
  
      $submit = ['title' => $submit_title, 'link' => $submit_url];
      $response = ['title' => $title,'submit' => $submit ,'links'=> $data];
      return $response;
    }

}




