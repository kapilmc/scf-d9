<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
// use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "configapi_resource",
 *   label = @Translation("Config API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-config"
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



     $response = ['message' => 'get config api'];


    return new ResourceResponse($response);






    $response['title'] = $this->getVariable('scfp_teamup_calendar_title') ? $this->getVariable('scfp_teamup_calendar_title') : 'Explore the Strategy & Corporate Finance Events';
    $response['teamup_email_subscription'] = $this->getVariable('scfp_teamup_calendar_email') ? $this->getVariable('scfp_teamup_calendar_email') : 'strategy_platform@mckinsey.com';
    $response['team_base_url'] = $this->getVariable('scfp_teamup_calendar_base_url') ? $this->getVariable('scfp_teamup_calendar_base_url') : 'https://teamup.com';
    $response['header']['key'] = $this->getVariable('scfp_teamup_calendar_header_key') ? $this->getVariable('scfp_teamup_calendar_header_key') : 'Teamup-token';
    $response['header']['value'] = $this->getVariable('scfp_teamup_calendar_header_value') ? $this->getVariable('scfp_teamup_calendar_header_value') : '';
    $response['api_team_url'] = $this->getVariable('scfp_teamup_calendar_api_url') ? $this->getVariable('scfp_teamup_calendar_api_url') : '';
    $response['calendar_key'] = $this->getVariable('scfp_teamup_calendar_key') ? $this->getVariable('scfp_teamup_calendar_key') : '';
    $response['calendar_password'] = $this->getVariable('scfp_teamup_calendar_password') ? $this->getVariable('scfp_teamup_calendar_password') : '';
    dd($response);
    $cal_ids = $this->getVariable('scfp_teamup_subcalendar_ids') ? $this->getVariable('scfp_teamup_subcalendar_ids') : [];
    $response['calendar_ids'] = '';
    if(!empty( $cal_ids)) {
      $cal_ids_selected = array_filter($cal_ids, function($a) { return ($a !== 0); });
      $calendar_ids =  '';
      foreach ($cal_ids_selected as $val) {
        $calendar_ids .= '&subcalendarId[]='.$val;
      }
    }
    $response['calendar_ids']  = $calendar_ids;

    $links =  DB::table('scfp_teamup_ordering as n')
      ->select(['n.link_title as title','n.link_url as link'])
      ->orderBy('n.weight', 'asc')
      ->get();
    $meet_our_people_default_tab = 0;
    $meet_our_people_default_tab = $this->getVariable('meet_our_people_default_tab');

    $meet_our_people_first_alert = 0;
    $meet_our_people_first_alert = $this->getVariable('meet_our_people_first_alert');

    $meet_our_people_expert = 0;
    $meet_our_people_expert = $this->getVariable('meet_our_people_expert');

    $factiva_api = '';
    $factiva_api = $this->getVariable('scfp_factiva_api_config');

    $heap_id = '';
    $heap_id = $this->getVariable('scfp_heap_id');

    $links = ['tabs' => $links];
    $response = array_merge($response,$links);

    $min_banner_nid = '';
    $min_banner_data = $this->getAltVariable('scfp_mini_banner_document');
    if(!empty($min_banner_data)) {
      $min_banner_nid = @trim($min_banner_data);
    }
    // if(!empty($min_banner_data)) {
    //   $temp = @str_replace(']', "", $min_banner_data);
    //   $temp = @explode('[nid:', $temp);
    //   $min_banner_nid = @trim($temp[1]);
    // }
    
    $site_email = $this->getVariable('site_mail');
    return [
    'teamup'=> $response,
    'meet_our_people_default_tab' => $meet_our_people_default_tab,
    'meet_our_people_first_alert' => $meet_our_people_first_alert,
    'meet_our_people_expert' => $meet_our_people_expert,
    'factiva_api' => $factiva_api,
    'heap_id' => $heap_id,
    'site_email' => !empty($site_email) ? urlencode($site_email) : urlencode('S&CF_Platform@mckinsey.com'),
    'engagement' => ['creation_tooltips' => $this->getVariable('engagement_creation_tooltips')],
    'industry_developments' => ['tabs' => $this->get_industry_developments_tabs_data()],
    'burger_meet_our_people_nid' => trim($this->getVariable('scfp_burger_meet_our_people_nid')),
    'burger_key_documents_and_first_alert_nid' => trim($this->getVariable('scf_burger_key_documents_and_first_alert_nid')),
    'burger_finance_tools_analytical_offerings_nid' => trim($this->getVariable('scf_burger_finance_tools_analytical_offerings_nid')),
    'key_document' => ["title" => $this->getVariable('scfp_key_document_title'), "id" => trim($this->getVariable('scfp_key_document_nid'))],
    'meet_our_people_external_advisory' => trim($this->getVariable('meet_our_people_external_advisory')),
    'min_promotion' => [
                      'show' => $this->getAltVariable('scfp_nini_banner_display') == 0 ? true : false, 
                      'text' => $this->getAltVariable('scfp_nini_banner_text'), 
                      'resource_id' => $min_banner_nid,
                      ],
    ];
  

























  }

}

?>