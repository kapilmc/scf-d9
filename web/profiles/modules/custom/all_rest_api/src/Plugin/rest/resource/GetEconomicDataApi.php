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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;



/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "GetEconomicDataApi",
 *   label = @Translation("Get Economic Data Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-economic-data"
 *   }
 * )
 */


class GetEconomicDataApi extends ResourceBase {
    // get-economic-data/{country}/{indicator}

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {
  
    $link = '';
    $country = urldecode($country);
    $indicator = str_replace('-', ' ', $indicator);
    $response = $this->getData($country,$indicator);
    //dd($response);
    $response1 = $this->fetchPlatformLink();
    // dd($response1);
   if(isset($response1[0])) {
     $link = $response1[0]['link'];
   }
    $meta = ['info_text'=>$link];


  $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  

  $results = new ResourceResponse($data);
       $results->addCacheableDependency($data);
      return $results;


    // return jsonSuccess($response, $meta);

}


      public function getData($country, $indicator) {
        // public function getData() {

        // dd('sxdalmclas');
        $response = [];
        $nodes = \Drupal::database()->select('csvImporter' ,'csv');
        $nodes->fields('csv', ['country','indicator_name','scenarios_name','units','display_units','value'])

            // ->where('country',$country)
            // ->where('indicator_name',$indicator)

            // ->condition('csv.country',$country)
            // ->condition('csv.indicator_name',$indicator);
           ->condition('csv.country','japan')
              ->condition('csv.indicator_name','GDP');

            $results =  $nodes->execute()->fetchAll();

//  dd($results);

    
            // ->get();

           
      foreach($results as $key => $node) {
        // dd($node);

        // $data[]=[
        //     'country'=>$node->country,
        //     'indicator_name'=>$node->indicator_name,
        //     'scenarios_name'=>$node->scenarios_name,
        //     'units'=>$node->units,
        //     'display_units'=>$node->display_units,
        //     'value'=>$node->value,

        // ];

      
        // // dd($node);
        $response[0]['country'] = $node->country;
        $response[0]['indicator_name'] = $node->indicator_name;
        $response[0]['scenarios_name'] = $node->scenarios_name;
        $response[0]['units'] = $node->units;
        $response[0]['display_units'] = $node->display_units;
        $response[0]['years'][$node->year] = $node->value;
       }

    //    dd($data);
    //    dd($response);
         return $response;
         }
  













  public function fetchPlatformLink() {
    $response = [];
    $link = \Drupal::database()->select('variable', 'v');
    $link->condition('name','csvimporter-economic-analytic-link');
    $link ->distinct();
    $link->fields('v', ['value']);
    $results = $link->execute()->fetchAll();
   //  $link->fields( 'v',['value'])->execute();
   // ->get(array('value as value'));
    foreach($results as $key => $node) {


      $pos = strpos($node->value,'"');
     $substring = substr($node->value, $pos+1);
      $response[$key]['link'] = substr($substring,0,-2);

      
     }
    return $response;
   }

  }









 

?>