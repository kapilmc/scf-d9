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
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "feedbackdisplayapi_resource",
 *   label = @Translation("Feedback Display API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/feedback-display"
 *   }
 * )
 */


class FeedbackDisplayApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {


    
  $response = $this->displayFeedback();
 
  $meta = ['info_text'=>'Display Feedback'];

  $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];

  $results = new ResourceResponse($data);
    $results->addCacheableDependency($data);
   return $results;
  
  }

//   public function displayFeedback($user, $request)

//   {


    public function displayFeedback(){
       
      
        // $current_time = \Drupal::time()->getCurrentTime('d');

     
        $last_access = \Drupal::database()->select('users_field_data', 'u')
       ->condition('u.uid', 0, '<>')
       
      // ->condition('u.uid', $user->uid)
      ->range(0, 1)
       ->fields('u', [ 'name',  'access'])
    //  $query->field('u',[ 'access']);

      
       ->execute()->fetchAll();
      //  dd($query);
      //  $last_access = $query->execute()->fetchAll();

       
      //  dd($last_access);
       // dd('dskjbjkvcfs');

    //   $last_access = DB::table('users as u')
    //   ->where('u.uid', $user->uid)
    //   ->limit(1)
    //   ->value('u.access');

   dd($last_access);
      $last_access_time = strtotime(date('d-m-Y h:i:s a', $last_access));
dd($last_access_time);
      $current_time = strtotime(date('d-m-Y h:i:s a', time()));

      $diff = floor(($current_time - $last_access_time) / 60);
    //   dd($diff);

      // Hits count
      $count = \Drupal::database()->select('scf_api_hits_count' ,'h')
      ->condition('f.uid', $user->uid)
      ->range(0, 1)
      ->value('h.count');
      $count = !empty($count) ? $count : 0;

    // Last feedback submit check
      $three_months_back = strtotime(date('d-m-Y h:i:s a', time()) .' -3 months');
      $last_feedback = (bool)\Drupal::database()->select('scf_feedback' , 'f')
      // ->where('f.uid', $user->uid)
      ->condition('f.uid', $user->uid)
      ->range(0, 1)
      ->orderBy('timestamp', 'DESC')
      ->value('f.timestamp');

      $check_old_feedback = (bool)\Drupal::database()->select('scf_feedback' , 'f')
      ->condition('f.uid', $user->uid)
      ->range(0, 1)
      ->orderBy('timestamp', 'DESC')
      ->value('f.timestamp');
      if ($check_old_feedback) {
          if ($count > 40 && ($last_feedback < $three_months_back)) {
              return true;
          }
      } else {
          if ($count > 40) {
              return true;
          }
      }
      return false;
  }









}

?>