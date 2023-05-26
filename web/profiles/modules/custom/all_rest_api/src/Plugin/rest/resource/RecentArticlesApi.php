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
 *   id = "Recent Articles api",
 *   label = @Translation("Recent Articles Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-recent-articles"
 *   }
 * )
 */


class RecentArticlesApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {
dd('hgjgjdhds');


    $response = $this->fetchContent();

    $meta = ["info_text" => 'See all Recent Activities'];
   
     $data= [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  

    $results = new ResourceResponse($data);
     return $results;



  }


//   public function getData(RecentArticlesRepository $repository,  Request $request)
//   {
//     $user = $this->getUser($request);
//     $response = $repository->fetchContent($request, $user);
//     $meta = ["info_text" => 'See all Recent Activities'];
//     return jsonSuccess($response, $meta);
//   }





 public function fetchContent($request, $user) {
    dd('test');
    $response = [];
    $user_id = '';
    $first_timestamp = $acces_timestamp = 0;
    $limit = ($request->query('limit') && $request->query('limit') > 0) ? $request->query('limit') : 100000;
    $offset = ($request->query('offset') && $request->query('offset') >= 0) ? $request->query('offset') : 0;

    if(is_object($user)) {
      $user_id = $user->uid;
    }
    // Fetch user's last access notification timestamp
    $timestamp =  DB::table('scfp_users_tracking as u')
      ->where('u.uid', $user_id)
      ->select(['u.access as notification_access', 'u.first_access as first_access'])
      ->get();
    $timestamp = reset($timestamp);
    if(is_object($timestamp)) {
      $acces_timestamp  = $timestamp->notification_access;
      $first_timestamp  = $timestamp->first_access;
    }
    $engagement_id = 0;

    // Fetch nodes that were updated after user's last notification access.
    $query = DB::table('scfp_articles_tracking as n')
      ->where('n.changed', '>=', $first_timestamp)
      ->select(['n.nid as nid','n.type as type'])
      ->skip($offset*$limit)
      ->take($limit)
      ->orderBy('changed','desc');

    // if (!$show_all) {
    //   $query->where('n.changed', '>=', $timestamp );
    // }

    $nodes = $query->get(['b.nid', 'n.type']);

    $response['items'] = [];
    foreach($nodes as $key => $node) {
      if($node->type === RESOURCES_CONTENT_TYPE) {
        $type = ['type' => DOCUMENT];
        $temp  = $type + $this->getResourceDocumentsByNid($node->nid, $engagement_id);

        if(isset($temp['added_toolbox'])){
          unset($temp['added_toolbox']);
        }

        if(isset($temp['text']) || $temp['text'] === Null){
          unset($temp['text']);
        }

        if(isset($temp['summary'])|| $temp['summary'] === Null){
          unset($temp['summary']);
        }

        if(isset($temp['bookmarked'])){
          unset($temp['bookmarked']);
        }
        if(isset($temp['is_secondary'])){
          unset($temp['is_secondary']);
        }

        if(isset($temp['authors'])){
          unset($temp['authors']);
        }

        if(!empty($temp) && isset($temp['trophy'])){
          unset($temp['trophy']);
        }
        $response['items'][] = $temp;
      }
      if($node->type === NEWS_AND_ARTICLE_CONTENT_TYPE) {
        $type = ['type' => ARTICLE];
        $temp  = $type + $this->getArticleData($node->nid, $engagement_id);

        if(isset($temp['bookmarked'])){
          unset($temp['bookmarked']);
        }
        $response['items'][] = $temp;
      }

      if($node->type === EXPERT_CONTENT_TYPE) {
        $type = ['type' => EXPERT];
        $temp  = $type + $this->getExpertData($node->nid, $engagement_id);

        if(isset($temp['bookmarked'])){
          unset($temp['bookmarked']);
        }
        $response['items'][] = $temp;
      }
    }

    $response['recent_count'] = $this->getRecentActivitiesCount($acces_timestamp);
    $response['total_count'] = $this->getRecentActivitiesTotalCount($first_timestamp);

    return $response;
  }

  /**
  * Get Recent activities count
  * @param Int $timestamp Timestamp
  * @return Int $count
  */
  public function getRecentActivitiesCount($timestamp)
  {
    return DB::table('scfp_articles_tracking as n')
         ->where('n.changed', '>=', $timestamp )
         ->count();
  }

  /**
  * Get Recent total count
  * @return Int $count
  */
  public function getRecentActivitiesTotalCount($timestamp)
  {
    return DB::table('scfp_articles_tracking as n')
         ->where('n.changed', '>=', $timestamp )
         ->count();
  }
  

}

?>