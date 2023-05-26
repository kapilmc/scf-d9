<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
use Drupal\Core\Database\Database;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "enganments_rest_resource",
 *   label = @Translation("Engagments Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/engagements/1"
 *   }
 * )
 */
class EngagementsApi extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('example_rest'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get() {




    // $datas = ['message' => 'Hello, this is a rest engagements  Api  to calls'];


    // return new ResourceResponse($response);



//     $query = \Drupal::database()->select('node', 'n');
//     // $query->condition('n.type', 'engagement');
//     $query->condition('n.type', 'resource');
//     $query->addField('n', 'nid','title');
//     // $query->orderBy("revision_id", 'ASC');
//          $query->range(0, 20);
//  $results = $query->execute()->fetchAll(); 
//     dd($results);
//     // drupal_set_message('value' .print_r($results, true));
//  foreach ($results as $result) {
//     // dd($result);
//      $data[] = array(

//      'nid'=>$result->nid->value,
//     //  'title'=>$result->name->value,

//      ); 
   
//     }

//     $response = new ResourceResponse($data);
//     $response->addCacheableDependency($data);
//     return $response;
// -----------------
 



    // public function getData($uid) {
        // $isActiveEng = $this->isOneEngActive($uid);
        // if(!$isActiveEng) {
        // //   DB::table('engagement_user')
        //   \Drupal::database()->select('engagement_user')
        //     ->where('uid', $uid)
        //     ->where('is_no', 1)
        //     ->update(['is_default' => 1]);
        // } 
    //     return $this->getUserEngagement($uid);
    // //   }
    
    //   public function getUserEngagement($uid) {
        // $response = $data = $active = $justbrowsing = $other = $all_data = [];
        // $result = DB::table('node as n')
        $result =  \Drupal::database()->select('node as n');
        // dd($result);
       
        $result ->Join('engagement_user as eu','eu.eid' ,'=' ,'n.nid');
        $result ->leftJoin('field_data_field_company as c','c.entity_id' ,'=' ,'n.nid');
        $result ->leftJoin('field_data_field_just_browsing as jb','jb.entity_id' ,'=' ,'n.nid');
        $result ->leftJoin('field_data_field_charge_code as ch','ch.entity_id' ,'=' ,'n.nid');
        $result->leftJoin('field_data_field_industry as ind','ind.entity_id' ,'=' ,'n.nid');
        $result ->leftJoin('field_data_field_country as country','country.entity_id' ,'=' ,'n.nid');
        $result ->leftJoin('field_data_field_strategy_area as area','area.entity_id' ,'=' ,'n.nid');
        $result ->leftJoin('field_data_field_engagement_duration as duration','duration.entity_id' ,'=' ,'n.nid');
        $result->leftJoin('field_data_field_json_data as json_data','json_data.entity_id' ,'=' ,'n.nid');
        // $result->where('n.status',self::PUBLISHED);
        $result->where('n.type','engagement');
        $result->where('eu.uid',$uid);
        $result->orderBy('n.created','asc');
          dd($result);
        $result ->select(array('n.nid as id',
        // $result->fields(array('n.nid as id',

    
          'n.title as title',
          'c.field_company_value as company',
          'jb.field_just_browsing_value as just_browsing',
          'ch.field_charge_code_value as charge_code',
          'ind.field_industry_tid as industry_tid',
          'country.field_country_tid as country_tid',
          'area.field_strategy_area_tid as area_tid',
          'duration.field_engagement_duration_value as start_date',
          'duration.field_engagement_duration_value2 as end_date',
          'eu.is_author as is_author',
          'json_data.field_json_data_value as company_json',
    ));
    // dd($result);
    // $result = $query->execute()->fetch()
    // $data = $result->execute();
        // ->get();
        dd($result);
        // foreach ($result as $key => $val) {
        //   $response[$key]['id'] = $val->id;
        //   $response[$key]['title'] = $val->title;
        //   $response[$key]['active'] = $this->isDefaultEng($val->id, $uid);
        //   $response[$key]['company'] = $val->company;
        //   $response[$key]['is_author'] = ($val->is_author == 0) ? false : true;
        //   $response[$key]['charge_code'] = $val->charge_code;
        //   $response[$key]['industry'] = ['id'=>$val->industry_tid,'title'=> $this->getTermName($val->industry_tid)];
        //   $response[$key]['country'] = ['id'=>$val->country_tid,'title'=> $this->getTermName($val->country_tid)];
        //   $response[$key]['area'] = ['id'=>$val->area_tid,'title'=> $this->getTermName($val->area_tid)];
        //   $response[$key]['duration'] = ['start_date'=> $this->getDateFormat($val->start_date),'end_date'=> $this->getDateFormat($val->end_date)];
        //   $response[$key]['people'] = $this->get_enagament_user($val->id);
        //   $response[$key]['just_browsing'] = ($val->just_browsing == 0) ? false : true;
        //   $response[$key]['company_json'] = json_decode($val->company_json);
        //   $data[$val->id] = $response[$key];
        //   if($response[$key]['active'] == 1 ) {
        //     $active[$val->id] = $response[$key];
        //   } else if($response[$key]['just_browsing'] == 1) {
        //     $justbrowsing[$val->id] = $response[$key];
        //   } else {
        //     $other[$val->id] = $response[$key];
        //   }
        // }   
        // $all_data = array_merge($justbrowsing, $active, $other);
        // return ['engagements' => $all_data];



    //   }
    
    //   /**
    //    * Check acitive engagement
    //    */
    //   public function isDefaultEng($eid, $uid) {
    //     return (bool) DB::table('engagement_user as eu')
    //         ->where('eu.eid', $eid)
    //         ->where('eu.uid', $uid)
    //         ->limit(1)
    //         ->value('eu.is_default');
    //   }
    
    //   /**
    //    * Check user's any engagement acitive or not
    //    */
    //   public function isOneEngActive($uid) {
    //     return (bool) DB::table('engagement_user as eu')
    //         ->where('eu.uid', $uid)
    //         ->where('eu.is_default', 1)
    //         ->limit(1)
    //         ->value('eu.eid');
    //   }
    
    // /**
    //  * get_enagament_user description
    //  * @param  Integer $eid Engagement ID
    //  * @return Array   $data  People array
    //  */
    //   public function get_enagament_user($eid) {
    //     $data = [];
    //     $result = DB::table('engagement_user as eu')
    //         ->leftJoin('users as u','u.uid' ,'=' ,'eu.uid')
    //         ->where('eu.eid', $eid)
    //         ->where('u.uid','>', 0)
    //         ->get(['eu.uid', 'u.name', 'eu.is_author']);
    //         foreach ($result as $key => $user_details) {
    //           $profile_pic = '';
    //           $profile_pic = $this->getUserProfile($user_details->uid);
    //           $is_author = ($user_details->is_author == 0) ? false : true;
    //           $data[] = ['id' => $user_details->uid, 'title' => $user_details->name, 'image_src' => $profile_pic, 'is_author' => $is_author];
    //         }
    //         return $data;
    //   }
    
    //   public function getUsersData($eng_id, $string = '', $user) {
    //     $matches = $merge_matches = array();
    //     if ($string) {
    //       $string = urldecode($string);
    //       $result = DB::table('users as u')
    //       ->leftJoin('field_data_field_person_id as p','p.entity_id' ,'=' ,'u.uid')
    //       ->where('u.name','LIKE', $string. '%')
    //       ->select(array('u.uid as uid', 'u.mail as mail', 'u.name as name', 'p.field_person_id_value as person_id'))
    //       ->limit(10)
    //       ->get();
    
    //       // Checking user in the CMS system.
    //       $rows = array();
    //       foreach ($result as $key => $user_details) {
    
    //         if ($user->uid === $user_details->uid) {
    //           continue;
    //         }
    //         $matches[] = array(
    //           'name' => $user_details->name,
    //           'person_id' => $user_details->person_id,
    //           'email' => $user_details->mail,
    //           );
    //       }
    
    //       // Fetching user from JSON file.
    //       $json_matches = $this->engagement_invitations_json_data($string);
    //       if (!empty($matches) && !empty($json_matches)) {
    //         // Merging the array from DB and JSON file.
    //         $merge_matches = array_merge($matches, $json_matches);
    //       }
    //       elseif (empty($matches) && !empty($json_matches)) {
    //         $merge_matches = $json_matches;
    //       }
    //       elseif (!empty($matches) && empty($json_matches)) {
    //         $merge_matches = $matches;
    //       }
    
    //       //Removing those users from dropdown which are already added in group.
    //       // $already_added = _strategy_engagement_get_users_list_by_engagement($engagement_id);
    //       // $count_numbers = count($already_added);
    //       // $indexes = array();
    //       // if($count_numbers > 0) {
    //       //   for($k = 0; $k < $count_numbers; $k++) {
    //       //       $email = $already_added[$k]->email;
    //       //       $name  = $already_added[$k]->name;
    //       //       foreach($merge_matches as $key => $value) {
    //       //         if($value['email'] === $email && $value['name'] === $name){
    //       //           $indexes[] = $k;
    //       //         }
    //       //       }
    //       //   }
    //       // }
    //       // if (count($indexes) > 0) {
    //       //   foreach($indexes as $key => $value) {
    //       //     unset($merge_matches[$value]);
    //       //   }
    //       // }
    //       $final_matches = array_values($merge_matches);
    //       $stored_in_arr = array();
    //       $final_unique_matches = array_filter($final_matches,
    //         function($el) use (&$stored_in_arr, $user) {
    //           if (strtolower($user->mail) == strtolower($el['email'])) {
    //             return FALSE;
    //           }
    //           else if (in_array(strtolower($el['email']), $stored_in_arr)) {
    //               return false;
    //           }
    //           else if (in_array($el['email'], $stored_in_arr)) {
    //               return false;
    //           } else {
    //               $stored_in_arr[] = $el['email'];
    //               return true;
    //           }
    //         }
    //       );
    //       // Limiting the result to only 10.
    //       $merge_matches = array_slice($final_unique_matches, 0, 10, TRUE);
    //     }
    
    
    //     return array_values($merge_matches);
    //   }
    
    //   public function engagement_invitations_json_data($string = '') {
    //     $string = urldecode($string);
    //     $result = $matches = [];
    //     $uri = $_SERVER['DOCUMENT_ROOT'].'/sites/default/files/people_json/people_json.json';
    //     $read_json_file = $this->_strategy_read_json($uri);
    //     $json_decode_data = json_decode($read_json_file, TRUE);
    //     $match = array();
    //     foreach($json_decode_data as $key => $value) {
    //       if ($this->startsWith($value['name'], $string) !== FALSE) {
    //         $match[] = $value;
    //       }
    //     }
    
    //     // Limiting the result to only 10.
    //     $result = array_slice($match, 0, 10, TRUE);
    //     if (!empty($result)) {
    //       foreach ($result as $person_email => $ppl_info) {
    //         $matches[] = array(
    //           'name' => $ppl_info['name'],
    //           'person_id' => $ppl_info['person_id'],
    //           'email' => $ppl_info['email'],
    //         );
    //       }
    //     }
    
    //     return $matches;
    //   }
    
      
















// $datas['engagements'=>$resulds];






    // $meta = ['info_text'=>'Engagements'];
     
    // $data = [ 'status'=>"success", 'data'=>$datas, 'meta'=>$meta];
      
      
      
      







    // $response = new ResourceResponse($data);
    // $response->addCacheableDependency($data);
    // return $response;
  }

}