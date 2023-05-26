<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Database\Database;
// use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "Hit Page Team",
 *   label = @Translation("Hit Page Team "),
 *   uri_paths = {
 *     "canonical" = "/api/v1/hit-page-team"
 *   }
 * )
 */


class HitPageTeamApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {
    

    $response['core team'] = $this->getCoreTeam();
    $response['key contact'] = $this->getKeyContact();

    // $response = $this->getExpertById();
   
    // // $data = \Drupal::service('all_reat_api.ExpertService')->getExpertById();
    // $data = \Drupal::service('all_reat_api.ExpertService')->firstAlertExpertQueryBuilder();
   
    // dd($data);


   


    $meta = ['info_text' => 'HIT Team'];
   
    $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
      $results = new ResourceResponse($result);
     return $results;

  }


  public function getCoreTeam()
  {
   
    $response = [];
    $config = \Drupal::config('hit_page_core_team.settings');
    $response['title'] = $config->get('hit_core_team_title') ? $config->get('hit_core_team_title') : '';
    $data = [];



    // $nodes = DB::table('scf_hit_team_ordering as p') 
    $connection = \Drupal::database();
    $query = $connection->select('scf_hit_team_ordering', 'p');
    $query ->join('node_field_data', 'n', 'n.nid = p.nid');
    $query->condition('n.type', 'experts');
    $query->condition('n.status', 1);
    $query->condition('p.key_contact', 0);
    $query->fields('n', ['nid']);
    $query->orderBy('p.weight', 'asc');
    $query->range(0, 16);
    // $query->fields('n',['nid','title']);
    $results = $query->execute()->fetchAll();
    // dd($results);
    


    $data = [];
    foreach ( $results as $key => $node) {

    //   $data[] = $this->getExpertById('', $node->nid, 3);
    //   $data[] = $this->getExpertById('', $node->nid, 3);

    //   $data['nid'] = $node->nid;

      $data[]=[
        'nid'=>$node->nid,
        // 'name'=> $node->title,

      ];
      

    }
    $response['data'] = $data;


    // dd($response);
    return  $response;
  }






  public function getKeyContact()
  {
    // dd('asmnc');
    $response = [];

    $config = \Drupal::config('hit_page_key_contact.settings');
   
    $response['title'] = $config->get('hit_key_contact_title') ? $config->get('hit_key_contact_title') : '';
    $data = [];

    $connection = \Drupal::database();
    $query = $connection->select('scf_hit_team_ordering', 'p');
    $query ->join('node_field_data', 'n', 'n.nid = p.nid');
    $query->condition('n.type', 'experts');
    $query->condition('n.status', 1);
    $query->condition('p.key_contact', 1);
    $query->fields('n', ['nid']);
    $query->orderBy('p.weight', 'asc');
    $query->range(0, 16);
    $results = $query->execute()->fetchAll();

    // dd($results);
    
    foreach ($results as $node) {
      // dd($node);
      // $data[] = $this->getExpertById('', $node->nid, 3);
    //   $data=[];
    //   $data['nid'] = $node->nid;



      $data[]=[
        'nid'=>$node->nid,
        // 'name'=> $node->title,

      ];
    }



 $response['data'] = $data;

    return  $response;


  }




// }


//   public function getExpertById($engagement_id, $nid, $num = 3)
//   {

    public function getExpertById()
    {

   // dd(',csnbvsnb');
    //   $expert_index = env('EXPERTS_SOLR_INDEX', 'experts');
    //   $client =  \App\Util\StrategySolr::getInstance();
       // get a select query instance
       $query = $client->createSelect();
    //    $query->addFilterQuery(array('key'=>'index_id', 'query'=>'index_id:'.$expert_index));
       $query->addFilterQuery(array('key'=>'is_nid', 'query'=>'is_nid:'.$nid));
       $query->addFilterQuery(array('key'=>'is_status', 'query'=>'is_status:1'));
       // this executes the query and returns the result
       $resultset = $client->select($query);
      
       $response = [];
       $documents = [];
     
      foreach ($resultset as $document) {
           $temp = [];
           $is_external = false;
           $is_email = false;
           $nid = 0;
           $email = '';
           $body = '';
           $text = '';
           $tenure = '';
           $location_office = '';
           $location_country = '';
           $title = '';
           $last_name= '';
           $role = '';
           $role_text = '';
           $external_profile_pic = '';
           $profile_url = '';
           $profile_pic = '';
           $core_sectors = '';
           $cv = '';
           $topics = [];

          foreach ($document as $field => $value) {
              if (is_array($value)) {
                  $value = implode(', ', $value);
              }

              if ($field == 'is_field_is_external_expert') {
                  $is_external = (!empty($value) && $value == 1) ? true : false;
              }

              if ($field == 'is_field_is_email_contact') {
                  $is_email = (!empty($value) && $value == 1) ? true : false;
              }

              if ($field == 'is_nid') {
                  $nid = (!empty($value) && $value > 0) ? $value : 0;
              }

              if ($field == 'tm_field_tenure') {
                  $tenure = !empty($value)  ? $value : '';
              }

              if ($field == 'tm_field_location_office') {
                  $location_office = !empty($value)  ? $value : '';
              }

              if ($field == 'tm_field_location_country') {
                  $location_country = !empty($value)  ? $value : '';
              }

              if ($field == 'tm_title') {
                  $title = !empty($value)  ? $value : '';
              }

              if ($field == 'tm_field_last_name') {
                  $last_name = !empty($value)  ? $value : '';
              }

              if ($field == 'tm_field_expert_role$name') {
                  $role = !empty($value)  ? $value : '';
              }

              if ($field == 'tm_field_unique_role') {
                  $role_text = !empty($value)  ? $value : '';
              }
                             
              if ($field == 'tm_field_email') {
                  $email = !empty($value)  ? strip_tags($value) : '';
              }

              if ($field == 'tm_body$value') {
                  $body = !empty($value)  ? strip_tags($value) : '';
              }

              if ($field == 'is_field_external_profile_pic$file') {
                  $default_pic = SITE_PATH .'/sites/all/themes/scfp/images/userpic.jpg';
                  $external_profile_pic = !empty($value)  ? $this->getFilepathFromFid($value) : $default_pic;
              }

              if ($field == 'is_field_fmno') {
                  $profile_url = !empty($value)  ? 'https://profiles.intranet.mckinsey.com/person/'.base64_encode($value) : '';
              }

              if ($field == 'tm_field_profile_pic') {
                  $profile_pic = !empty($value)  ? $this->replaceHTTPwithHTTPS($value) : '';
              }

              if ($field == 'tm_field_core_sectors') {
                  $core_sectors = !empty($value)  ? strip_tags($value) : '';
              }

              if ($field == 'is_field_expert_profile$file') {
                  $cv = (!empty($value) && $value > 0)  ? SITE_PATH . '/download/file/' . $value : '';
              }

              if ($field == 'im_scfp_expert_cat_topics') {
                  $topics = $this->getExpertTopics($value);
              }

              if ($nid > 0) {
                  if ($is_external) {
                      $temp = [
                      'id' => $nid,
                      'text' => $body,
                      'is_external' => $is_external,
                      'is_email' => $is_email,
                      'role' => '',
                      'role_text' => '',
                      'tenure' => '',
                      'location' => '',
                      'country' => '',
                      'firstname' => $title,
                      'lastname' => $last_name,
                      'profile_pic' => $external_profile_pic,
                      'industry' => [],
                      'function' => [],
                      'geography' => [],
                      'contact' => [],
                      'assistant' => [],
                      'topics' => $topics,
                      'profile_url' => '',
                      'core_sectors' => $core_sectors,
                      'cv' => $cv,
                      'email' => '',
                      'bookmarked' => false,
                      ];
                  } elseif ($is_email) {
                      if (empty($title) && empty($last_name)) {
                          $title = trim($email);
                      }
                      $temp = [
                      'id' => $nid,
                      'text' => '',
                      'is_external' => $is_external,
                      'is_email' => $is_email,
                      'role' => '',
                      'role_text' => '',
                      'tenure' => '',
                      'location' => '',
                      'country' => '',
                      'firstname' => $title,
                      'lastname' => $last_name,
                      'profile_pic' => $external_profile_pic,
                      'industry' => [],
                      'function' => [],
                      'geography' => [],
                      'contact' => [],
                      'assistant' => [],
                      'topics' => $topics,
                      'profile_url' => '',
                      'core_sectors' => '',
                      'cv' => '',
                      'email' => urlencode(trim($email)),
                      'bookmarked' => false,
                      ];
                  } else {
                       $temp = [
                       'id' => $nid,
                       'text' => '',
                       'is_external' => $is_external,
                       'is_email' => $is_email,
                       'role' => $role,
                       'role_text' => $role_text,
                       'tenure' => $tenure,
                       'location' => $location_office,
                       'country' => $location_country,
                       'firstname' => $title,
                       'lastname' => $last_name,
                       'profile_pic' => $profile_pic,
                       'industry' => $this->getExpertIndustry($nid, $num),
                       'function' => $this->getExpertFunction($nid, $num),
                       'geography' => $this->getExpertGeography($nid),
                       'contact' => $this->getExpertContact($nid),
                       'assistant' => $this->getExpertAssistant($nid),
                       'topics' => $topics,
                       'profile_url' => $profile_url,
                       'core_sectors' => '',
                       'cv' => '',
                       'email' => '',
                       'bookmarked' => false,
                       ];
                  }
              }

              if (!empty($temp)) {
                  $documents = $temp;
              }
          }
          $response = $documents;
      }
       return $response;
  }


}


