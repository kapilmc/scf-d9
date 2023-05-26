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



/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "home page newsresource",
 *   label = @Translation("Home Page News  API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/home-page-news"
 *   }
 * )
 */


class HomePageNewsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {


    $response = $this->getHomePageNews();

    $meta = ['info_text'=>'Home page news'];
   
     $data= [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  

    $results = new ResourceResponse($data);
 
     //  return  jsonsuccess($results);
     return $results;



  }



  public function getHomePageNews(){



    $query = \Drupal::database()->select('scf_home_page_news_ordering' , 'h');
    // $query ->where('h.enabled', 1);
    $query->condition('h.enabled', 1);
    $query ->orderBy('h.weight', 'asc');
    $query->range(0,2);

     $query->fields('h' , [ 
     'fid',
     'link', 
     'external',
     ]);

// dd($query);
$result = $query->execute()->fetchAll();

    // dd($result );

    $results = [];
    foreach($result as $item) {
        // dd($item);

        $fids = $item->fid;
        $file = \Drupal\file\Entity\File::load($fids); 
        // $file_relative_url = $file->createFileUrl();
       $path = $file->createFileUrl(FALSE);
    //   dd($path);


     
     $results[] = array(
       
        'image'=> $path,
        'link'=> $item->link,
        // 'image' => $this->getFilepathFromFid($item->fid),
        'is_external'=> $item->external,
      );
      // dd($result);
    }

    return $results;

  }


}

?>