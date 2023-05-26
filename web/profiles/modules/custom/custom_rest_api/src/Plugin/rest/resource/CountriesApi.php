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
use Drupal\Core\Database\Driver\mysql;
use Drupal\Core\Database\Database;
/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "countriesapi_resource",
 *   label = @Translation("countries API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-countries"
 *   }
 * )
 */


class CountriesApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {


    // // \Drupal::database()->select
    //  $response = ['message' => 'Hello, this is a rest service and podcast   Api  to calls'];


    // return new ResourceResponse($response);
  // }

    // \Drupal::database()->select

// $home='lkcjxlnvjdsnmv dngnjesdlgns';
// dd($home);
  

    $query = \Drupal::database()->select ('csvImporter' , 'csv');
    // $query->range(0,20);/
    $query->fields('csv', [ 
  
      'country',
    'indicator_name',
  ]);

    
    dd($query);


    $result = $query->execute()->fetchAll();
    
dd($result);








    // public function getData($country,$indicator) {
      $response = [];
      // $nodes = DB::table('csvImporter as csv');
      $nodes =\Drupal::database()->select('csvImporter','csv');

      // dd($nodes);
      $nodes ->where('country','=' ,$country);
      $nodes ->where('indicator_name','=' ,$indicator);
          // dd($nodes);
          // ->get();
          
          $result = $nodes->еxecute()->fetchAll();
          
          dd($result);
    foreach($nodes as $key => $node) {
      $response[0]['country'] = $node->country;
      $response[0]['indicator_name'] = $node->indicator_name;
      $response[0]['scenarios_name'] = $node->scenarios_name;
      $response[0]['units'] = $node->units;
      $response[0]['display_units'] = $node->display_units;
      $response[0]['years'][$node->year] = $node->value;               
    }
    // dd($response);
    return $response;
  // }

  // public function getCountryData() {
    $response = [];
    $response1 = [];
    $count = '';
    $default_countries = ['United States', 'Germany', 'India','China'];
    // $countries = DB::table('csvImporter as csv')
    $countries =\Drupal::database()->select('csvImporter' , 'csv')
      ->where('is_country','=' ,1)
      ->distinct()
      ->orderBy('csv.country','asc')
      ->get(array('country as country'));
    foreach($countries as $key => $node) {
      $response['countries'][$key] = $node->country;
      $count = ++$key;
    }
    // $regions = DB::table('csvImporter as csv')
    $regions = \Drupal::database()->select('csvImporter' , 'csv')
      ->where('is_country','=' ,0)
      ->distinct()
      ->get(array('country as country'));
    foreach($regions as $key => $node) {
      $response['regions'][$key] = $node->country;
    }
    // $indicator_name = DB::table('csvImporter as csv')
    $indicator_name = \Drupal::database()->select('csvImporter' , 'csv')
      ->distinct()
      ->get(array('indicator_name as indicator_name','display_units as display_units'));
    foreach($indicator_name as $key => $node) {
      $active = false;
      $api_code = strtolower(preg_replace('#[ -]+#', '-', $node->indicator_name));
      if($key === 0) {
        $active = true;
      }
      $response['indicators'][$api_code]['label'] = $node->indicator_name;
      $response['indicators'][$api_code]['api_code'] = $api_code;
      $response['indicators'][$api_code]['subtitle'] = $node->display_units;
      $response['indicators'][$api_code]['active'] = $active;
    }
    $response['default'] = $default_countries;
    return $response;
  // }

  // public function getRegionData() {
    $response = [];
    // $countries = DB::table('csvImporter as csv')
    $countries = \Drupal::database()->select('csvImporter' ,'csv')
      ->where('is_country','=' ,0)
      ->distinct()
      ->get(array('country as country'));
    foreach($countries as $key => $node) {
      $response[$key]['country'] = $node->country;
    }
    return $response;
  // }


  // $default_countries = ['United States', 'Germany', 'India','China'];



 $datas=['countries'=>$countries,'default'=> $default_countries,'indicators'=>$indicators,'regions'=>$regions];

    $meta = ['info_text'=>'$link'];
     
    $data = [ 'status'=>"success", 'data'=>$datas, 'meta'=>$meta];
      
      
      
      
      
      
      // dd($result);
          $response = new ResourceResponse($data);
          $response->addCacheableDependency($data);
          return $response;
        
      
      



}




      
  }










    
  
 

?>