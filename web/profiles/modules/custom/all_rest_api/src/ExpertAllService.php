<?php

namespace Drupal\all_rest_api;

use Drupal\Core\Session\AccountInterface;


use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Database\Database;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;

/**
 * Class ExpertAllService
 * @package Drupal\all_rest_api\ExpertAllService
 */
class ExpertAllService {

  protected $currentUser;

  /**
   * ExpertAllService constructor.
   * @param AccountInterface $currentUser
   */
  public function __construct(AccountInterface $currentUser) {
    $this->currentUser = $currentUser;
  }


  /**
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public function getExpertData() {
    return $this->currentUser->getDisplayName();
  }


  /**
  * Get Expert
  * @return Array $response
  */
    public function getExpert($engagement_id, $expert_id) {
        return $this->getExpertById($engagement_id, $expert_id, 5);
    }

  /**
  * Get Author/Contact
  * @return Array $response
  */
    public function getAuthor($author_id) {
        return $this->getAuthorById($author_id, 5);
    }

  /**
  * Get First Alert & Expert querybuilder
  * @return QueryBulder
  */
    // public function firstAlertExpertQueryBuilder($cat, $topic, $inspire) {
        public function firstAlertExpertQueryBuilder() {
            
            // dd('dbtest');





        // return DB::table('scf_expert_ordering as r')
        $query = \Drupal::database()->select('scf_expert_ordering', 'r');
       

        // ->whereIn('r.cat', $cat);
        // ->whereIn('r.topic', $topic);
        $query->condition('r.enabled', 1);
        $query->Join('node', 'n', 'r.nid = n.nid');
        // ->where('n.status', self::PUBLISHED);
        $query->orderBy('r.weight', 'asc');
        // $query->orderBy('n.created', 'asc');
        $results = $query->execute()->fetchAll();
        dd($results);
        // ->distinct();
      
    }

  /**
  * Get First Alert & Expert
  * @return Array $response
  */
    public function getFirstAlertExpert($engagement_id, $request) {
        $response = [];
        $limit = ($request->query('limit') && $request->query('limit') > 0) ? $request->query('limit') : 0;
        $offset = ($request->query('offset') && $request->query('offset') >= 0) ? $request->query('offset') : 0;

        $cat = $request->has('category') ? $request->get('category') : [];
        $topic = $request->has('topics') ? $request->get('topics') : [];
        $inspire = $request->has('inspire') ? $request->get('inspire') : [];

        $cat = !empty($cat) ? $cat : [0];
        $topic_temp = !empty($topic) ? $topic : '';
        $inspire_temp = !empty($inspire) ? $inspire : [0];

        if ($inspire_temp[0] > 0) {
            $sevent_step_vid = $this->getVidfromVocabMachineName('7_building_block');
            $isSevenStepID = (bool) DB::table('taxonomy_term_data as t')
            ->where('vid', $sevent_step_vid)
            ->where('tid', $inspire_temp[0])
            ->limit(1)
            ->value('t.name');

            if ($isSevenStepID) {
                // Bussiness Strategy
                $bussiness_strategy_tid = $this->getTermTidFromUUID('0ae477df-c402-41dc-8c19-4b64df89b99f');
                $inspire_temp = [$bussiness_strategy_tid];
            }
        }
    
        $topic = !empty($topic_temp) ? $topic_temp : $inspire_temp;

        $nodes = [];
        $query = $this->firstAlertExpertQueryBuilder($cat, $topic, $inspire);
        $count = $query->count();
        if ($limit > 0) {
            $nodes = $query->skip($offset*$limit)
                    ->take($limit)
                    ->select(array('r.nid as id'))
                    ->get();
        } else {
            $nodes = $query->select(array('r.nid as id'))
                    ->get();
        }
        $experts = [];
        foreach ($nodes as $node) {
            $experts[] = $this->getExpertById($engagement_id, $node->id, 3);
        }

        $experts_email = [];
        foreach ($experts as $key => $expert) {
            if ($expert['is_email']) {
                $experts_email[] = $expert;
                break;
            }
        }
        $response = [
          'experts' => !empty($experts_email) ? $experts_email : $experts,
          'count' => !empty($experts_email) ? 1 : $count
        ];
        return $response;
    }

    

    // public function getExpertById($engagement_id, $nid, $num = 3) {
        public function getExpertById() {

      dd('testing');
         
        $expert_index = env('EXPERTS_SOLR_INDEX', 'experts');
        $client =  \App\Util\StrategySolr::getInstance();
         // get a select query instance
         $query = $client->createSelect();
         $query->addFilterQuery(array('key'=>'index_id', 'query'=>'index_id:'.$expert_index));
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
