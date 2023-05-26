<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Database\Database;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "homepagenews_resource",
 *   label = @Translation("Home Page News  API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/home-page-news"
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
    // \Drupal::database()->select
    // ->fields('h',['h.fid', 'h.link', 'h.external']);



    $query = \Drupal::database()->select('scf_home_page_news_ordering' , 'h');
    // $query ->where('h.enabled', 1);
    $query ->orderBy('h.weight', 'asc');
    $query->range(0,2);

$query->fields('h' , [ 
  'fid',
 'link', 
 'external',
]);

dd($query);
$result = $query->execute()->fetchAll();

    // dd($result );


    foreach($result as $item) {
     
     $result[] = array(
       
        'id'=> $item->fid->value,
        'link'=> $item->link->value,
      
        // 'image' => $this->getFilepathFromFid($item->fid),

        'is_external'=> $item->external->value,
      );
      dd($result);
    }

// dd($result);


    // $data = [];
    // // $items = DB::table("scf_home_page_news_ordering as h")
    // $items = \Drupal::database()->select("scf_home_page_news_ordering ")
    // ->where('scf_home_page_news_ordering.enabled', 1)
    // ->orderBy('scf_home_page_news_ordering.weight', 'asc')
    // // ->limit(2)
    // ->range(0,2)
    // // ->fields('h',['h.fid', 'h.link', 'h.external']);
    // ->fields('scf_home_page_news_ordering',['fid', 'link', 'external']);
    // // ->get(['h.fid', 'h.link', 'h.external']);
    // foreach($items as $item) {
    //   $datas[] = [

    //     'link' => $item->link,
    //     'image' => $this->getFilepathFromFid($item->fid),
    //     'is_external' => $item->external,
    //   ];
    // }
    // dd($data);


  //  return $data;





















          
  $meta = ['info_text'=>'Home page news'];
              $data = [ 'status'=>"success", 'data'=>$result, 'meta'=>$meta];
                
            
                
                
                
                
                // dd($result);
                    $response = new ResourceResponse( $data);
                    $response->addCacheableDependency( $data);
                    return $response;
                  
                


         



  
  }


}

?>