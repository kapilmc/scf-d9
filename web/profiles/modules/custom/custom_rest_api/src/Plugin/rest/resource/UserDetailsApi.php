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
use \Drupal\Core\Database\Connection;
use Drupal\user\Entity\User;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "customuserapi_resource",
 *   label = @Translation("cutomuser API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/user-details"
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
    //dd($allusers);
$users = User::loadMultiple($allusers);


 //dd($users);

$result = [];
foreach($users as $user){
  // dump($user->uid)
// dd($user->name->value);

// dump($user->name->value);
if($user->uid->value=='1'){
  $result[] = array(
    
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
    'profile_pic'=>$user->profile_pic->value,
    'location'=>$user->field_user_location->value,
    'role'=>$user->field_user_role->value,
    'callouts'=>$user->callouts->value,
    'current_journey'=>$user->current_journey->value,

    
  );
  
}
}

    // $term=['terms'=>$result];

    $meta = ['info_text'=>'User Details'];
  
  $data = [ 'status'=>"success", 'data'=>$result, 'meta'=>$meta];
    
    
// dd($result);
    $response = new ResourceResponse($data);
    $response->addCacheableDependency($data);
    return $response;
  

  
  }

}

?>