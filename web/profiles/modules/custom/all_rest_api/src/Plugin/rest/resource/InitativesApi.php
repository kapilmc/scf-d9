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
 *   id = "Initatives Api",
 *   label = @Translation("Initatives Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get_initatives"
 *   }
 * )
 */
class InitativesApi extends ResourceBase {

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {

      
      $response = $this->getInitatives();
      $meta = ['info_text' => 'Get Initatives'];
      $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
        $results = new ResourceResponse($result);  
        //  return  jsonsuccess($results);
      return $results;

    }

    public function getInitatives(){
        $response = [];
            
        $config = \Drupal::config('scfp_initiatives.settings');

        $fid = $config->get('image1');
        $file = File::load($fid[0]);
        $path = $file->createFileUrl(FALSE);

        $fid = $config->get('image2');
        $file = File::load($fid[0]);
        $paths = $file->createFileUrl(FALSE);

         $fid = $config->get('image2');
         $file = File::load($fid[0]);
         $pathss = $file->createFileUrl(FALSE);
         $response[] = [
            'url' => $config->get('url1') ? $config->get('url1') : '',
            'image' => $path,
            ];
            $response[] = [
                'url' => $config->get('url2') ? $config->get('url2') : '',
                'image' => $paths,
                ];
                $response[] = [
                    'url' => $config->get('url2') ? $config->get('url3') : '',
                    'image' => $pathss,
                    ];
                    return $response;


    }

}




