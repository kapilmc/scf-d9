<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;
use \Drupal\Core\Database\Connection;
use Drupal\user\Entity\User;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "user api resource",
 *   label = @Translation("user API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/user-details"
 *   }
 * )
 */


class UserDetailsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

   $response = $this->userdata();


    // $user = $this->getUser($request);
    // $response = $this->getUserDetails($user);
    $meta = ['info_text'=>'User Details'];



    $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
    
    
    // // dd($result);
        $response = new ResourceResponse($data);
        $response->addCacheableDependency($data);
        return $response;



    // return jsonSuccess($response, $meta);


    




  }
  public function userdata(){

    $allusers = \Drupal::entityQuery('user')
    // ->condition('status', 1)
    // ->condition('uid'=> 1)
    ->condition('roles', 'administrator')
    // ->condition('roles', 'anonymous')
    // ->condition('roles', 'authenticated')
    // ->condition('roles', 'firm_member')
    // ->condition('roles', 'content_manager')
    // ->condition('roles', 'contractor')
    // ->condition('roles', 'expert_for_chat')
    // ->condition('roles', 'super_admin')
    ->execute();
    // dd($allusers);
$users = User::loadMultiple($allusers);

// dd($users);

$result = [];
foreach($users as $user){
  // dump($user->uid)
// dd($user->name->value);

// dump($user->name->value);
if($user->uid->value=='1'){
  $result = array(
    
    'id'=>$user->uid->value,
    'fmno'=>$user->field_user_fmno->value,
    'is_expert'=>$user->is_expert->value,
    // 'uuid'=>$user->uuid->value,
    // 'pass'=>$user->pass->value,
    'name'=>$user->name->value,
    'onboarded'=>$user->onboarded->value,
    'mail'=> $user->mail->value,
    'mck_position'=>$user->mck_position->value,
    'mck_region'=>$user->mck_region->value,
    // 'timezone'=>$user->timezone,
    // 'status'=>$user->status,
    // 'created'=>$user->created,
    // 'changed'=>$user->changed,
    // 'access'=>$user->access,
    // 'login'=>$user->login,
    // 'init'=>$user->init->value,
    // 'roles'=>$user->roles->value,
    'person_id'=>$user->field_person_id->value,
    'profile_pic'=>$user->user_picture->value,
    'location'=>$user->field_user_location->value,
    'role'=>$user->field_user_role->value,
    'callouts'=>$user->callouts->value,
    'current_journey'=>$user->current_journey->value,
    // 'cureent_journey'=> $this->getCurrentUserJourney($user->uid),
    // 'field_user_profile_image'=>$user->field_user_profile_image->value,
    // 'user_picture'=>$user->user_picture->value,
 
    
  );
  
}
}
return $result;

//     // $term=['terms'=>$result];

//     $meta = ['info_text'=>'User Details'];
  
//   $data = [ 'status'=>"success", 'data'=>$result, 'meta'=>$meta];
    
    
// // dd($result);
//     $response = new ResourceResponse($data);
//     $response->addCacheableDependency($data);
//     return $response;
  

  
  }

// ---------------




  public function getUserDetails($user)
  {
    // dd('sckadmc');
    $response = [];
    $fmno = '';
    // $fmno = $this->getFieldValue('field_user_fmno', $user->uid, 'user', $data = 'value');
    if(empty($fmno)) {
      $fmno = isset($_SERVER['HTTP_FMNO']) ? $_SERVER['HTTP_FMNO'] : '';
    }

    $response['id'] = $user->uid;
    $response['mail'] = $user->mail;
    dd($response);
    $response['name'] = $this->getUsername($user->uid);
    $person_id = $this->getFieldValue('field_person_id', $user->uid, 'user', $data = 'value');
    $response['person_id'] = !empty($person_id) ? $person_id : '';
    $response['fmno'] = str_pad($fmno,5,0,STR_PAD_LEFT);
    $profile_pic = $this->getUserProfile($user->uid);
    $response['profile_pic'] = $profile_pic;
    $response['onboarded'] = $this->isOnboarded($user->uid);
    $response['current_journey'] = $this->getCurrentUserJourney($user->uid);
    $response['role'] = $this->getFieldValue('field_user_role', $user->uid, 'user', $data = 'value');
    $response['location'] = $this->getFieldValue('field_user_location', $user->uid, 'user', $data = 'value');
    $response['callouts'] = $this->getCalloutsStatus($user->uid);
    $response['mck_region'] = isset($_SERVER['HTTP_REGION_CODE']) ? urldecode($_SERVER['HTTP_REGION_CODE']) : '';
    $response['mck_position'] = isset($_SERVER['HTTP_POSITION_CODE']) ? $_SERVER['HTTP_POSITION_CODE'] : '';
    $response['is_expert'] = $this->isChatExpert($user->uid);
    $response['visits_track'] = $this->getVisitsTrack($user->uid);

    return $response;
  }

  /**
   * Get User session visits
   * @param  integer $uid
   * @return  Array callouts
   */
  public function getVisitsTrack($uid) {
    $visits = \Drupal::database()->select('scfp_visits_track')->where('uid', $uid)->first();
    $track = ['win_lead' => '', 'inspire' => ''];

    if ($visits) {
      $visits_track =  unserialize($visits->visits);
      foreach($visits_track as $key => $value) {
        $visits_track[$key] = $value;
      }

      return array_merge($track, $visits_track);
    }

    return $track;
  }

  /**
   * Set User session visits
   * @param  integer $uid
   * @return  Array $visits
   */
  public function setVisitsTrack($uid, $visits) {
    $visits = [$visits['visited'] => $visits['timestamp']];
    $visits_track = \Drupal::database()->select('scfp_visits_track')->where('uid', $uid)->first();

    if ($visits_track) {
      $visits_track = unserialize($visits_track->visits);
      $visits_track = array_merge($visits_track, $visits);
      return \Drupal::database()->select('scfp_visits_track')
        ->where('uid', $uid)
        ->update(['visits' => serialize($visits_track)]);
    }

    return \Drupal::database()->select('scfp_visits_track')->insert(['uid' => $uid, 'visits' => serialize($visits)]);
  }

  /**
   * Get User callout status
   * @param  integer $uid
   * @return  Array callouts
   */
  public function getCalloutsStatus($uid) {
    $callout = \Drupal::database()->select('scf_callout_track')->where('uid', $uid)->first();
    if ($callout) {
      return unserialize($callout->callouts);
    }

    return [];
  }

  /**
   * Set User callout status
   * @param  integer $uid
   * @param  Array $callouts
   */
  public function setCalloutsStatus($uid, $callouts) {
    $callouts = array_unique($callouts);
    $exist = \Drupal::database()->select('scf_callout_track')->where('uid', $uid)->count();

    if ($exist) {
      return \Drupal::database()->select('scf_callout_track')
        ->where('uid', $uid)
        ->update(['callouts' => serialize($callouts)]);
    }

    return \Drupal::database()->select('scf_callout_track')->insert(['uid' => $uid, 'callouts' => serialize($callouts)]);
  }

  /**
   * Set current user journey
   * @param  integer $uid
   * @param  String $journey
   */
  public function setCurrentUserJourney($uid, $journey) {
    $current_journey = \Drupal::database()->select('scf_current_journey')->where('uid', '=', $uid)->first();

    if (!$current_journey) {
      \Drupal::database()->select('scf_current_journey')->insert([
        'uid' => $uid,
        'journey' => $journey,
      ]);
    } else {
      \Drupal::database()->select('scf_current_journey')
      ->where('uid', $uid)
      ->update([
        'journey' => $journey,
      ]);
    }
  }

  /**
   * Get current user journey
   * @param  integer $uid
   * @return  String $journey
   */
  public function getCurrentUserJourney($uid) {
    $journey = \Drupal::database()->select('scf_current_journey')
    ->condition('uid', '=', $uid)
    ->execute()->fetchAll();

    return $journey ? $journey->journey : '';
  }

  /**
   * onBoardingStatus
   * @param  integer $uid
   * @return Array
   */
  public function onBoardingStatus($uid) {
    $response = [];
    if(!$this->isOnboarded($uid)) {
      $time = time();
      \Drupal::database()->select('scf_onboarding_stats')->insert(['uid' => $uid, 'timestamp' => $time]);
      $response = ['onboarded' => true];
    }
    return $response;
  }

  /**
   * isOnboarded
   * @param Integer $uid
   * @return boolean
   */
  public function isOnboarded($uid)
  {
    return (bool)\Drupal::database()->select('scf_onboarding_stats as u')
        ->where('u.uid', $uid)
        ->limit(1)
        ->value('u.timestamp');
  }

  /**
   * isChatExpert
   * @param Integer $uid
   * @return boolean
   */
  public function isChatExpert($uid = 0)
  {
    $roles = [];
    $roles = $this->getRoles($uid);
      if(in_array(SCFP_EXPERT_FOR_CHAT, $roles)) {
        return true;
      } else {
        return false;
      }
  }



































}

?>