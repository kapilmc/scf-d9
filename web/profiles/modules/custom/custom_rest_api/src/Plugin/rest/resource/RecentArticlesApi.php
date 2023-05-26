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

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "Recent_articles_resource",
 *   label = @Translation("Recent Articles  API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-recent-articles"
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
  public function get() 
  {

   

  //   $ids = \Drupal::entityQuery('node')
  // ->accessCheck(TRUE)
  // ->condition('type', 'article')
  // // dd($ids);
  // ->execute();

  //   dd($ids);





    // public function fetchContent($request, $user)
    // {
      // $response = [];
      // $user_id = '';
      // $first_timestamp = $acces_timestamp = 0;
      // $limit = ($request->query('limit') && $request->query('limit') > 0) ? $request->query('limit') : 100000;
      // $offset = ($request->query('offset') && $request->query('offset') >= 0) ? $request->query('offset') : 0;
      //$show_all = ($request->query('show_all') && $request->query('show_all') >= 0) ? $request->query('show_all') : 0;
  
      // if(is_object($user)) {
      //   $user_id = $user->uid;
      // }
      // // Fetch user's last access notification timestamp
      // $timestamp =  DB::table('scfp_users_tracking as u')
      $timestamp = \Drupal::database()->select('scfp_users_tracking' , 'u')
      // dd($timestamp);
        ->where('u.uid', $user_id);
        dd($timestamp);
        // ->select(['u.access as notification_access', 'u.first_access as first_access']);
        // dd( $timestamp);
        // ->get();
      $timestamp = reset($timestamp);
      if(is_object($timestamp)) {
        $acces_timestamp  = $timestamp->notification_access;
        $first_timestamp  = $timestamp->first_access;
      }
      $engagement_id = 0;
  
      // Fetch nodes that were updated after user's last notification access.
      // $query = DB::table('scfp_articles_tracking as n')
      $query = \Drupal::database()->select('scfp_articles_tracking' , 'n')
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
    // }
  
    /**
    * Get Recent activities count
    * @param Int $timestamp Timestamp
    * @return Int $count
    */
    // public function getRecentActivitiesCount($timestamp)
    // {
      // return DB::table('scfp_articles_tracking as n')
      return \Drupal::database()->select('scfp_articles_tracking' , 'n')
           ->where('n.changed', '>=', $timestamp )
           ->count();
    // }
  
    /**
    * Get Recent total count
    * @return Int $count
    */
    // public function getRecentActivitiesTotalCount($timestamp)
    // {
      // return DB::table('scfp_articles_tracking as n')
      return \Drupal::database()->select('scfp_articles_tracking','n')

           ->where('n.changed', '>=', $timestamp )
           ->count();





























    $items=['items'=>$result];

  $meta = ['info_text'=>'See all Recent Activities'];

$data = [ 'status'=>"success", 'data'=>$items, 'meta'=>$meta];
  
  
  
    $response = new ResourceResponse($data);
    $response->addCacheableDependency($data);
    return $response;
  

    }
  }



?>