<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;
use Drupal\Core\Database\Database;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Component\Serialization;
use Symfony\Component\Serializer\Encoder;
use Drupal\Component\Serialization\Json;


/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "demo_rest_resource",
 *   label = @Translation("Demo rest resource"),
 *   uri_paths = {
 *     "canonical" = "/check/api/demo"

 *   }
 * )
 */
class DemoApi extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
    public function get() {
//         die('Api testing code to emplement get method');

// $result = ['message'=>'Api testing code to emplement get '];

    // return new ResourceResponse($result);

//   }

// get database table data get in rest api

 

$query = \Drupal::database()->select('csvImporter' , 'csv');

        $query->fields('csv', [
              
            'country',
            'indicator_name',
          ]);
          dd( $query);     

$result = $query->execute()->fetchAll();


 foreach ($result as $data){
     $rows[] = [
               
                 'country' => $data->country,
                 'indicator_name'=>$data->indicator_name,
                
                
            ];
        }


    $response = new ResourceResponse($rows);
    $response->addCacheableDependency($rows);

    return $response;

 }



}


