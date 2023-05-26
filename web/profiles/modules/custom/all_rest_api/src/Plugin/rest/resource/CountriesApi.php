<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;

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
 *   id = "countries api resource",
 *   label = @Translation("Countries API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-countries"
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
     $link = " ";
     $response = $this->getCountryData();
   
     $response1 = $this->fetchPlatformLink();
    //  dd($response1);
     if(isset($response1[0])) {
       $link = $response1[0]['link'];
     }
     $meta = ['info_text'=>$link];

     $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];

       $results = new ResourceResponse($data);
         $results->addCacheableDependency($data);
        return $results;

    //  return jsonSuccess($response, $meta);

      
  }


public function getCountryData() {
  
  $response = [];
  // $response1 = [];
  // $count = '';
 
  $default_countries = ['United States', 'Germany', 'India','China'];
  $countries = \Drupal::database()->select('csvImporter','csv')
    ->condition('is_country' , 1)
    ->distinct()
    ->orderBy('csv.country','asc')

    ->fields('csv',['country'])
    ->execute()->fetchAll();
  foreach($countries as $key => $node) {
    
    $response['countries'][$key] = $node->country;
    $count = ++$key;

  }
  $regions = \Drupal::database()->select('csvImporter','csv')
    ->condition('is_country', 0)
    ->distinct()

   // ->get(array('country as country'));
    ->fields('csv',['country'])
    ->execute()->fetchAll();
  foreach($regions as $key => $node) {
    $response['regions'][$key] = $node->country;
  }
  $indicator_name = \Drupal::database()->select('csvImporter','csv')
    ->distinct()
    //->get(array('indicator_name as indicator_name','display_units as display_units'));
    ->fields('csv',['indicator_name','display_units'])
    ->execute()->fetchAll();
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