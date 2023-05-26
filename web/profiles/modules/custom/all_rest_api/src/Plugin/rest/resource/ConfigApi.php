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
 *   id = "config api resource",
 *   label = @Translation("Config API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-config"
 *   }
 * )
 */


class ConfigApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    $response = '';
    $response = $this->getTeamUpConfig();
  

    $meta = ['info_text' => 'config'];

    $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];

       $results = new ResourceResponse($result);
        return $results;

    
}



public function getTeamUpConfig() {

  
    // $config = \Drupal::config('scfp_teamup_calendar.settings');
    // // $config = \Drupal::config('scfp_meet_our_people_tab.settings');
    // // $config = \Drupal::config('factiva_api.settings');
    // // $config = \Drupal::config('scfp_heap.sttings');

     
    
    // dd($config);

    
    $response = [];

      
       $config = \Drupal::config('scfp_teamup_calendar.settings');
    //    $config = \Drupal::config('scfp_meet_our_people_tab.settings');
    //    $config = \Drupal::config('factiva_api.settings');
    //    $config = \Drupal::config('scfp_heap.sttings');
   
        
    
    $response['title'] = \Drupal::state()->get('scfp_teamup_calendar_title') ? $config->get('scfp_teamup_calendar_title') : 'Explore the Strategy & Corporate Finance Events';

    $response['teamup_email_subscription'] = $config->get('scfp_teamup_calendar_email') ? $config->get('scfp_teamup_calendar_email') : 'strategy_platform@mckinsey.com';
    $response['team_base_url'] = $config->get('scfp_teamup_calendar_base_url') ? $config->get('scfp_teamup_calendar_base_url') : 'https://teamup.com';
    $response['header']['key'] = $config->get('scfp_teamup_calendar_header_key') ? $config->get('scfp_teamup_calendar_header_key') : 'Teamup-token';
    $response['header']['value'] = $config->get('scfp_teamup_calendar_header_value') ? $config->get('scfp_teamup_calendar_header_value') : '';
    $response['api_team_url'] = $config->get('scfp_teamup_calendar_api_url') ? $config->get('scfp_teamup_calendar_api_url') : '';
    $response['calendar_key'] = $config->get('scfp_teamup_calendar_key') ? $config->get('scfp_teamup_calendar_key') : '';
    $response['calendar_password'] = $config->get('scfp_teamup_calendar_password') ?$config->get('scfp_teamup_calendar_password') : '';
   
    $cal_ids = $config->get('scfp_teamup_subcalendar_ids') ? $config->get('scfp_teamup_subcalendar_ids') : [];
    $response['calendar_ids'] = '';

    if(!empty( $cal_ids)) {
      $cal_ids_selected = array_filter($cal_ids, function($a) { return ($a !== 0); });
      $calendar_ids =  '';
      foreach ($cal_ids_selected as $val) {
        $calendar_ids .= '&subcalendarId[]='.$val;
      }
    }
    $response['calendar_ids']  = $calendar_ids;

    // $links =  DB::table('scfp_teamup_ordering as n')
    //   ->select(['n.link_title as title','n.link_url as link'])
    //   ->orderBy('n.weight', 'asc')
    //   ->get();



    // $data = [];     


      $connection = \Drupal::database();
      $query = $connection->select('scfp_teamup_ordering', 'n');
      $query->fields('n', ['link_title']);
      $query->fields('n', ['link_url']);
      $query->orderBy('n.weight', 'asc');
      $results = $query->execute()->fetchAll();
  
    $data = [];
    foreach ($results as  $res) {
       
      
       $data[] = [
     
      'title' => $res->link_title,
      'url' => $res->link_url,
     

       ];
    }





    
    
    $config = \Drupal::config('scfp_meet_our_people_tab.settings');
    

    $meet_our_people_default_tab = 0;
    $meet_our_people_default_tab = $config->get('meet_our_people_default_tab');

    $meet_our_people_first_alert = 0;
    $meet_our_people_first_alert = $config->get('meet_our_people_first_alert');
  
   
    $meet_our_people_expert = 0;
    $meet_our_people_expert = $config->get('meet_our_people_expert');
   
    $meet_our_people_external_advisory = 0;
    $meet_our_people_external_advisory = $config->get('meet_our_people_external_advisory');
 

    
   
    $config = \Drupal::config('factiva_api.settings');
    $factiva_api = '';
    $factiva_api = $config->get('scfp_factiva_api_config');
    


    $config = \Drupal::config('scfp_heap.sttings');
    $heap_id = '';
    $heap_id = $config->get('scfp_heap_id');






    $links = ['tabs' => $data];
    $response = array_merge($response,$data);
    

    $config = \Drupal::config( 'scfp_mini_banner.settings');
   


    $min_banner_nid = '';
    $min_banner_data = $config->get('scfp_mini_banner_document');
    if(!empty($min_banner_data)) {
      $min_banner_nid = @trim($min_banner_data);
    }


    $site_email = \Drupal::state()->get('site_mail');
    $configs = \Drupal::config('openid.settings');
    $config = \Drupal::config('key_document.settings');
    $configss = \Drupal::config('scfp_mini_banner.settings');



    
    return [

        
    'teamup'=> $response,
    'meet_our_people_default_tab' => $meet_our_people_default_tab,
    'meet_our_people_first_alert' => $meet_our_people_first_alert,
    'meet_our_people_expert' => $meet_our_people_expert,
    'meet_our_people_external_advisory' => $meet_our_people_external_advisory,
    
    'factiva_api' => $factiva_api,
    'heap_id' => $heap_id,
    'site_email' => !empty($site_email) ? urlencode($site_email) : urlencode('S&CF_Platform@mckinsey.com'),
    'engagement' => ['creation_tooltips' =>$configs->get('engagement_creation_tooltips')],
    'industry_developments' => ['tabs' => $this->get_industry_developments_tabs_data()],
    'burger_meet_our_people_nid' => trim($config->get('scfp_burger_meet_our_people_nid')),
    'burger_key_documents_and_first_alert_nid' => trim($config->get('scf_burger_key_documents_and_first_alert_nid')),
    'burger_finance_tools_analytical_offerings_nid' => trim($config->get('scf_burger_finance_tools_analytical_offerings_nid')),
    'key_document' => ["title" => $config->get('scfp_key_document_title'), "id" => trim($config->get('scfp_key_document_nid'))],
    // 'meet_our_people_external_advisory' => trim(\Drupal::state()->get('meet_our_people_external_advisory')),
    'min_promotion' => [
                      'show' => $configss->get('scfp_nini_banner_display') == 0 ? true : false, 
                      'text' => $configss->get('scfp_nini_banner_text'), 
                      'resource_id' => $min_banner_nid,
                      ],
    ];
  }





  /**
  * get_industry_developments_tabs_data
  */
  public function get_industry_developments_tabs_data() {
   
    return [
      'articles' => ['title' => 'Articles', 'active' =>  \Drupal::state()->get('strategy_articles_checkbox_articles') ? true : false],
      'news' => ['title' => 'News', 'active' => \Drupal::state()->get('strategy_articles_checkbox_news')? true : false],
      'materials' => ['title' => 'S&CF industry materials', 'active' => \Drupal::state()->get('strategy_articles_checkbox_industry_materials') ? true : false],
      'experts' => ['title' => 'S&CF industry contacts', 'active' => \Drupal::state()->get('strategy_articles_checkbox_industry_experts')? true : false],
      'practice' => ['title' => 'Industry practice', 'active' => \Drupal::state()->get('strategy_articles_checkbox_industry_practice')? true : false],
    ];
  }

}





?>