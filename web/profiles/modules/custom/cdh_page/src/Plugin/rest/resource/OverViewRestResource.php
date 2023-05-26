<?php

namespace Drupal\cdh_page\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "overview_rest_resource",
 *   label = @Translation("OverView Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/v1/overview"
 *   }
 * )
 */
class OverViewRestResource extends ResourceBase {
  /**
   * A current user instance which is logged in the session.
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $loggedUser;
  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $config
   *   A configuration array which contains the information about the plugin instance.
   * @param string $module_id
   *   The module_id for the plugin instance.
   * @param mixed $module_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A currently logged user instance.
   */
  public function __construct(
    array $config,
    $module_id,
    $module_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($config, $module_id, $module_definition, $serializer_formats, $logger);

    $this->loggedUser = $current_user;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $config, $module_id, $module_definition) {
    return new static(
      $config,
      $module_id,
      $module_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('/v1/overview'),
      $container->get('current_user')
    );
  }

  public function get() {

//      $config = \Drupal::config('cdh_page_overview.settings');
//     //  dd($config);

//     //  data
//     //  $sucess = ['all'=>$getconfig->get(data)

//    $sucess = ['title'=>$config->get('cdh_page_overview_title'),
//    'text'=>$config->get('cdh_page_overview'),
//    'footer_text'=>$config->get('cdh_page_overview_footer_text')

//    // cdh_page_overview
// //    cdh_page_overview_footer_text
// ];



//     // $result = ['title'=>$config->get('cdh_page_overview_title'),
//     // 'overview'=>$config->get('cdh_page_overview')

//     // cdh_page_overview

// // ];

// $meta = 'OverView';
  
//  $result = ['success'=> $sucess ,'meta_text'=>$meta ];
//       $response = new ResourceResponse($result);
//       $response->addCacheableDependency($result);
//       return $response;
    







//  $config = \Drupal::config('cdh_page_reach_out.settings');
// //  dd($config);


//    $sucess = ['title'=>$config->get('cdh_page_reach_out_title'),
//    'text'=>$config->get('text'),
//    'links'=>$config->get('links')


//   ];




// $meta = 'Rech out';
  
//  $result = ['success'=> $sucess ,'meta_text'=>$meta ];
//       $response = new ResourceResponse($result);
//       $response->addCacheableDependency($result);
//       return $response;
    




      $config = \Drupal::config('cdh_page_impact.settings');
      // dd($config);



   $sucess = ['title'=>$config->get('cdh_page_impact_title'),
   'image'=>$config->get('cdh_page_impact_file'),
  //  'links'=>$config->get('links')

//   $media = Media::load($mid);
// $fid = $media->cdh_page_impact_file>target_id;
// $file = File::load($fid);

// $url = $file->url();
// 'image'=>$config->get('$url'),

  ];




$meta = 'Impact';
  
 $result = ['success'=> $sucess ,'meta_text'=>$meta ];
      $response = new ResourceResponse($result);
      $response->addCacheableDependency($result);
      return $response;
    

  }

}