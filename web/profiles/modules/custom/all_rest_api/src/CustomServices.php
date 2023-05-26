<?php
namespace  Drupal\all_rest_api;

/**
* @file providing the service that generate random number between 1000 to 9999.
*
*/

class CustomServices {

 public function get_number()
 {
 	return rand(1000,9999);
 }
 
}








<!-- 
/**
 * @file providing the service that gives the node Id from Node table'.
 *
*/
namespace Drupal\all_rest_api;
use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;



class CustomServices {

  // private $Database;
  protected $currentUser;
   /**
   * CustomService constructor.
   * @param AccountInterface $currentUser
   */

	// public function __construct(Connection $connection, AccountInterface $currentUser) {
	//  $this->database = $connection;
  //  $this->currentUser = $currentUser;
	// }
  
  /**
    * Returns list of nids from node table according to passed user id.
  */



/**
* @file providing the service that generate random number between 1000 to 9999.
*
*/



    public function get_number()
    {
        return rand(1000,9999);
    }




    public function getData(){
        dd('clsdcnvsdl');
      

        $test = 'test servicess';
        return $test;

    }



  public function drupalise ($uid) {
  	$query = \Drupal::database()->select('node_field_data', 'nfd');
  	$query->fields('nfd', ['nid','title','type']);
  	// $query->condition('nfd.uid',$uid,'=');
    $query -> range(0,20);
  	$result = $query->execute()->fetchAll();
    // return the result
    // $results = new ResourceResponse($result);
    // return $result;



    $items = [];
    foreach($result as $node) {
    

      $items[] = [
   
        'nid'=>$node->nid,
        'title'=>$node->title,
        'type'=> $node->type,

      ];
    }
       return $items;
  }




// -----------------


  /**
     * Engagement accepted
     */
    const ACCEPTED = 1;

    /**
     * Engagement publish
     */
    const PUBLISHED = 1;

    /**
     * Default engagement
     */
    const IS_DEAFULT = 1;

    /**
     * Function to get variable value stored via drupal config
     * @param String $variable variable name
     * @return String
     */
    public function getVariable($variable)
    {
        return getVariable($variable);
    }

    /**
     * Function to get variable value stored in alternate_variable
     * @param String $variable variable name
     * @return String
     */
    public function getAltVariable($variable)
    {
        return getAltVariable($variable);
    }

    /**
     * Function to get file path
     * @param int $fid File ID
     * @return String
     */
    public function getFileUri($fid)
    {
        return DB::table('file_managed as f')
            ->where('f.fid', $fid)
            ->value('f.uri');
    }

    /**
     * Function to get file object
     * @param int $fid File ID
     * @return Object
     */
    public function getFile($fid)
    {
        return DB::table('file_managed as f')
            ->where('f.fid', $fid)
            ->first();
    }

    /**
     * Function to return field value
     * @param String $field_name Field name
     * @param Int $entity_id Entity id
     * @param String $entity_type Entity type
     *
     * @return Field value
     */
    public function getFieldValue($field_name, $entity_id, $entity_type, $data = 'value')
    {
        return $this->getFieldData($field_name, $entity_id, $entity_type, $data);
    }

    /**
     * Function to return Entity reference
     * @param String $field_name Field name
     * @param Int $entity_id Entity id
     *
     * @return Entity reference id
     */
    public function getFieldReference($field_name, $entity_id, $entity_type)
    {
        return $this->getFieldData($field_name, $entity_id, $entity_type, 'target_id');
    }

    /**
     * Get date field
     * @param String $field_name Field name
     * @param Int $entity_id Entity id
     * @param String $entity_type Entity type
     *
     * @return Object with start_date, end_date
     */
    public function getDateFieldValue($field_name, $entity_id, $entity_type, $delta = 0)
    {
        return DB::table('field_data_'.$field_name)
            ->where('entity_id', $entity_id)
            ->where('entity_type', $entity_type)
            ->where('delta', $delta)
            ->get([$field_name.'_value as start_date', $field_name.'_value2 as end_date']);
    }

    /**
     * Function to return field data
     * @param String $field_name Field name
     * @param Int $entity_id Entity id
     * @param String $entity_type Entity type
     * @param String $data maybe value, value2, target_id, fid etc.
     *
     * @return Field value
     */
    public function getFieldData($field_name, $entity_id, $entity_type, $data)
    {
        return DB::table('field_data_'.$field_name)
            ->where('entity_id', $entity_id)
            ->where('entity_type', $entity_type)
            ->value($field_name.'_'.$data);
    }

    /**
     * Function to return multi field value
     * @param String $field_name Field name
     * @param Int $entity_id Entity id
     * @param String $entity_type Entity type
     *
     * @return Array $items
     */
    public function getMultiFieldValue($field_name, $entity_id, $entity_type, $data = 'value')
    {
        $items = [];
        $field_data = $this->getMultiFieldData($field_name, $entity_id, $entity_type, $data);
        foreach ($field_data as $value) {
            $items[] = $value->value;
        }
        return $items;
    }

    /**
     * Function to return multi field data
     * @param String $field_name Field name
     * @param Int $entity_id Entity id
     * @param String $entity_type Entity type
     * @param String $data maybe value, value2, target_id, fid etc.
     *
     * @return Field value
     */
    public function getMultiFieldData($field_name, $entity_id, $entity_type, $data)
    {
        return DB::table('field_data_'.$field_name)
            ->where('entity_id', $entity_id)
            ->where('entity_type', $entity_type)
            ->orderBy('delta', 'asc')
            ->get([$field_name.'_'.$data .' as value']);
    }

    /**
     * Get user roles
     * @param Int $user_uid user uid
     *
     * @return Array $user_roles assoc array of role rid and name
     */
    public function getRoles($user_uid)
    {
        $items = DB::table("role as r")
            ->join('users_roles as ur', 'ur.rid', '=', 'r.rid')
            ->join('users as u', 'ur.uid', '=', 'u.uid')
            ->where('ur.uid', $user_uid)
            ->get(['r.rid', 'r.name']);

        $user_roles = [];
        foreach ($items as $item) {
            $user_roles[$item->rid] = $item->name;
        }

        return $user_roles;
    }

    /**
     * Check node exist or not
     * @param Int $nid Node nid
     *
     * @return Boolean
     */
    public function isNodeExist($nid)
    {
        return (bool) DB::table('node as n')
            ->where('n.nid', $nid)
            ->limit(1)
            ->value('n.nid');
    }

    /**
     * Check node published or not
     * @param Int $nid Node nid
     *
     * @return Boolean
     */
    public function isNodePublished($nid)
    {
        return (bool) DB::table('node as n')
            ->where('n.nid', $nid)
            ->where('n.status', self::PUBLISHED)
            ->limit(1)
            ->value('n.nid');
    }

    /**
     * Check publication exist with tid
     * @param Int $tid term id
     *
     * @return Boolean
     */
    public function isPublicationExistWithTid($tid)
    {
        return (bool) DB::table('node as n')
            ->join('taxonomy_index as ti', 'ti.nid', '=', 'n.nid')
            ->where('ti.tid', $tid)
            ->where('n.status', self::PUBLISHED)
            ->where('n.type', 'publications')
            ->limit(1)
            ->value('n.nid');
    }


    /**
     * Get term name
     * @param Int $tid Teram id
     *
     * @return String Term name
     */
    public function getTermName($tid)
    {
        $cache = getCache($tid, SCFP_TAXONOMY_TERM_DATA_CACHE);
        if ($cache) {
            return $cache['name'];
        } else {
            $term['name'] = DB::table('taxonomy_term_data')
                    ->where('tid', $tid)
                    ->value('name');
            if (isset($term['name']) && !empty($term['name'])) {
                setForeverCache($tid, $term, SCFP_TAXONOMY_TERM_DATA_CACHE);
                return $term['name'];
            }
            return '';
        }
    }

/**
 * getOverviewNID
 * @param  Integer $tid Topic Tid
 * @return Integer  Node Id of Overview Resource
 */
    public function getOverviewNID($tid)
    {
        return DB::table('field_data_field_overview_resource')
          ->where('entity_id', $tid)
          ->where('bundle', 'topics')
          ->where('entity_type', 'taxonomy_term')
          ->value('field_overview_resource_target_id');
    }

  /**
   * Get term name
   * @param Int $tid Teram id
   *
   * @return String Term name
   */
    public function getTermUUID($tid)
    {
        $cache = getCache($tid, SCFP_TERMS_UUID_FROM_TID);
        if ($cache) {
            return unserialize($cache);
        } else {
            $term['uuid'] = DB::table('taxonomy_term_data')
            ->where('tid', $tid)
            ->value('uuid');
            if (isset($term['uuid']) && !empty($term['uuid'])) {
                setForeverCache($tid, serialize($term['uuid']), SCFP_TERMS_UUID_FROM_TID);
            }
            return $term['uuid'];
        }
    }

  /**
   * getVocabMachineNameFromTid
   * @param Int $tid Teram id
   *
   * @return String machine_name
   */
    public function getVocabMachineNameFromTid($tid)
    {
        return DB::table('taxonomy_term_data as t')
        ->join('taxonomy_vocabulary as v', 'v.vid', '=', 't.vid')
        ->where('t.tid', $tid)
        ->limit(1)
        ->value('v.machine_name');
    }

  /**
   * getVidfromVocabMachineName
   * @param  String $vocab_name
   * @return String
   */
    public function getVidfromVocabMachineName($vocab_name)
    {
        $cache = getCache($vocab_name, SCFP_VID_FROM_MACHINE_NAME);
        if ($cache) {
            return unserialize($cache);
        } else {
            $name =  DB::table('taxonomy_vocabulary as t')
            ->where('t.machine_name', $vocab_name)
            ->value('t.vid');
            if (isset($name) && !empty($name)) {
                setForeverCache($vocab_name, serialize($name), SCFP_VID_FROM_MACHINE_NAME);
            }
            return $name;
        }
    }

  /**
   * Get term id from UUID
   * @param String $uuid Term id
   *
   * @return Integer tid
   */
    public function getTermTidFromUUID($uuid)
    {
        $cache = getCache($uuid, SCFP_TERMS_TID_FROM_UUID);
        if ($cache) {
            return unserialize($cache);
        } else {
            $term['tid'] = DB::table('taxonomy_term_data')
            ->where('uuid', $uuid)
            ->value('tid');
            if (isset($term['tid']) && !empty($term['tid'])) {
                setForeverCache($uuid, serialize($term['tid']), SCFP_TERMS_TID_FROM_UUID);
            }
            return $term['tid'];
        }
    }

    /**
    * Get formatted date
    * @param Int $date UNIX timestamp
    *
    * @return String Formatted date
    */
    public function getDateFormat($date)
    {
        return date(API_DTAE_FORMAT, $date);
    }

    public function getTermsFromVid($vid)
    {
        $title = [];
        $cache = getCache($vid, SCFP_TERMS_FROM_VID);
        if ($cache) {
            $terms = unserialize($cache);
            return $terms;
        } else {
            $terms = DB::table('taxonomy_term_data')
            ->where('vid', $vid)
            ->get(['name as title','tid as id']);
            foreach ($terms as $key => $value) {
                $title[$value->id] = $value->title;
            }
            setForeverCache($vid, serialize($title), SCFP_TERMS_FROM_VID);
        }
        return $title;
    }

    public function getFilepathFromFid($fid)
    {
        $path = '';
        $file_path = DB::table('file_managed')
        ->where('fid', $fid)
        ->get(['uri as path']);
        if (!empty($file_path)) {
            $key = key($file_path);
            $path = $file_path[$key]->path;
        }
        $host = SITE_PATH.'/sites/default/files/';
        $path = str_replace("public://", $host, $path);
        return $path;
    }

    public function getFiletypeFromFid($fid)
    {
        $type = '';
        $file_mime = DB::table('file_managed')
        ->where('fid', $fid)
        ->get(['filemime as type']);
        if (!empty($file_mime[0])) {
            $type = explode('/', $file_mime[0]->type);
        }
        if (is_array($type)) {
            $type = $type[0];
        }
        return $type;
    }

    public function getChildFromParentTid($tid)
    {
        $childs = DB::table('taxonomy_term_hierarchy as h')
        ->where('parent', $tid)
        ->join('taxonomy_term_data as t', 't.tid', '=', 'h.tid')
        ->leftJoin('field_data_field_icon_class as i', 'i.entity_id', '=', 't.tid')
        ->leftJoin('field_data_field_is_default as d', 'd.entity_id', '=', 't.tid')
        ->leftJoin('field_data_field_exclude_display as ex', 'ex.entity_id', '=', 't.tid')
        ->where(function ($query) {
          $query->where('ex.field_exclude_display_value', '=', 0)
          ->orWhereNull('ex.field_exclude_display_value');
      })
        ->orderBy('t.weight', 'asc')
        ->get(['name as title','t.tid as id','i.field_icon_class_value as icon_class','d.field_is_default_value as active']);
        return $childs;
    }
    public function getBusinessStrategyfromSevenBlocksUuid($uuid)
    {
        $uuid_7_building_terms = ['ce6f84e2-43b2-4488-9406-5089a426c775','8592cbb9-6e24-4b20-8b82-c8fd60c5f238','75217fb4-4b67-4d91-8e3d-936680ae66fd','6055eb7d-3fc2-48f3-bc1a-5b6e7c638dc6','348e35f8-3cdd-499a-8a79-e670e4d3436f','322f9645-1f76-4657-b5ad-bd0b62e21910','aee96fcc-64b7-4e4b-8da9-2d03a462d335','2ed7e324-e11e-47e8-ae40-c9c4379ea5a2'];
        if (in_array($uuid, $uuid_7_building_terms)) {
            return true;
        } else {
            return false;
        }
    }

    public function isTermActive($tid)
    {
        $active =  DB::table('field_data_field_is_default as d')
        ->where('d.entity_id', $tid)
        ->get(['field_is_default_value']);
        $active = isset(reset($active)->field_is_default_value) ? reset($active)->field_is_default_value : 0;
        if ($active) {
            return true;
        } else {
            return false;
        }
    }

    public function getAuthorById($author_id, $num = 3) {
        $data = [];
        if($author_id > 0 && $this->isPublished($author_id)) {
            $cache = getCache($author_id, SCFP_AUTHOR_API_DATA);
            if ($cache) {
                $data = unserialize($cache);
            } else {
                $author_contacts = DB::table('scf_author_mapping as a')
                ->where('a.nid', $author_id)
                ->value('a.apidata');

                if(!empty($author_contacts)) {
                   setForeverCache($author_id, $author_contacts, SCFP_AUTHOR_API_DATA);
                   $data =  !empty($author_contacts) ? unserialize($author_contacts) : [];
                }
            }

            if(!empty($data)) {
                $profile_pic = '';
                $default_pic = SITE_PATH .'/sites/all/themes/scfp/images/userpic.jpg';
                $data['profile_pic'] =  !empty($data['profile_pic']) ? $this->replaceHTTPwithHTTPS($data['profile_pic']) : $default_pic;
                $data['industry'] = array_slice($data['industry'], 0, $num);
                $data['function'] = array_slice($data['function'], 0, $num);
                $data['geography'] =  $data['geography'];
                $data['id'] = (int) $author_id;
            }
        }
        return $data;
    }

  /**
   * fetchAuthorDetails
   * @param  Int $nid
   * @return Array
   */
    public function fetchAuthorDetails($nid)
    {
        $data = [];
        $author_contact_data = [];
        $authors =  DB::table('field_data_field_authors as a')
        ->where('a.entity_id', $nid)
        ->where('a.bundle', 'resource')
        ->select(array('a.field_authors_target_id as author_id',))
        ->orderBy('a.delta', 'asc')
        ->get();
        foreach ($authors as $val) {
            $temp = $this->getAuthorById($val->author_id, 3);
            if(!empty($temp)) {
                $data[] =  $temp;
            }
        }
        return $data;
    }

  /**
  * Function to get node title
  * @param int $nid Node nid
  * @return String
  */
    public function getNodeTitle($nid)
    {
        return DB::table('node as n')
        ->where('n.nid', $nid)
        ->where('n.status', self::PUBLISHED)
        ->value('n.title');
    }

   /**
   * Check node published or not
   * @param int $nid Node nid
   * @return Bool
   */
    public function isPublished($nid)
    {
        return (bool)DB::table('node as n')
        ->where('n.nid', $nid)
        ->where('n.status', self::PUBLISHED)
        ->value('n.title');
    }
   /**
   * Get Resource Documents by node id
   * @param int $nid (Node Id)
   * @param int $eng_id (Engagement Id)
   * @param int $user_id (User Id)
   * @return array $data
   */
    public function getResourceDocumentsByNid($nid, $eng_id = 0, $user_id = 0, $is_child = false)
    {
        $data = null;
        $cache = getCache($nid, SCFP_NODE_RESOURCE_CACHE);
        if ($cache && $this->isPublished($nid)) {
            $node = unserialize($cache);
        } else {
            $nodes = DB::table('node as n')
            ->where('n.nid', $nid)
            ->where('n.status', self::PUBLISHED)
            ->LeftJoin('field_data_body as b', 'n.nid', '=', 'b.entity_id')
            ->LeftJoin('field_data_field_resource_label as l', 'n.nid', '=', 'l.entity_id')
            ->LeftJoin('field_data_field_know_id as know', 'know.entity_id', '=', 'n.nid')
            ->LeftJoin('field_data_field_pdf_viewer_file as preview', 'preview.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_is_know_preview_available as knowpreview', 'knowpreview.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_upload_downloadable as ud', 'ud.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_video as v', 'v.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_is_secondary_resource as s', 's.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_top_tool as t', 't.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_is_q_a as qa', 'qa.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_external_url as eu', 'eu.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_asset_type as at', 'at.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_do_not_display_preview as dp', 'dp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_disable_download as field_disable_download', 'field_disable_download.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_is_webcasts_podcasts as iwp', 'iwp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_podcast_webcast_category as cwp', 'cwp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_host_name as hwp', 'hwp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_is_external_pw as ewp', 'ewp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_hosted_link_pw as lwp', 'lwp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_date_of_the_podcast_webcas as dwp', 'dwp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_audio_video_upload as awp', 'awp.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_resource_label_know as rlk', 'rlk.entity_id', '=', 'n.nid')
            ->select(array('n.nid as id',
            'n.title as title',
            'field_resource_label_tid as category','b.body_value as text',
            'b.body_summary as summary',
            'know.field_know_id_value as know_id',
            'preview.field_pdf_viewer_file_fid as preview_id',
            'knowpreview.field_is_know_preview_available_value as hasknowpreview',
            'ud.field_upload_downloadable_fid as download_fid',
            'v.field_video_fid as video_fid',
            's.field_is_secondary_resource_value as is_secondary',
            't.field_top_tool_value as trophy',
            'qa.field_is_q_a_value as is_qa',
            'eu.field_external_url_url as ext_url',
            'at.field_asset_type_value as asset_type',
            'dp.field_do_not_display_preview_value as do_not_display_preview',
            'field_disable_download.field_disable_download_value as disable_download',
            'iwp.field_is_webcasts_podcasts_value as is_webcasts_podcasts',
            'cwp.field_podcast_webcast_category_tid as podcast_webcast_category',
            'hwp.field_host_name_value as podcast_webcast_host_name',
            'ewp.field_is_external_pw_value as is_external_pw',
            'lwp.field_hosted_link_pw_url as hosted_link_pw',
            'dwp.field_date_of_the_podcast_webcas_value as podcast_webcast_date',
            'awp.field_audio_video_upload_fid as podcast_webcast_audio_video',
            'rlk.field_resource_label_know_value as resource_label_know',
            ))
            ->get();
            if (isset($nodes['0'])) {
                $node = $nodes['0'];
                setForeverCache($nid, serialize($node), SCFP_NODE_RESOURCE_CACHE);
            }
        }

        if (!empty($node)) {
            $env = getVariable('know_api_environment');
            $download = getVariable($env . '_download_document');
            $preview = getVariable($env . '_preview_document');
            $charge_code = getVariable($env . '_chargecode');
            $asset_type = isset($node->asset_type) && !empty($node->asset_type) ? $node->asset_type : 'document';
            $doc_type = $this->getDocumentType($node->id, $is_child);

            //download_link
            $download_url = "";
            $file_details = [];
            if ($node->know_id > 0) {
                $download_url = str_replace(':know_id', $node->know_id, $download);
                $download_url = str_replace(':chargeCode', $charge_code, $download_url);
                $download_url = str_replace(':asset_type', $asset_type, $download_url);
                $keystring = getVariable('know_downlod_preview_new_old_api');
                if($keystring == 'new') {
                  $download_url = str_replace(':FMNO', '&fmno='.$this->getUserCryptFmno($user_id), $download_url);
                } else {
                  $download_url = str_replace(':FMNO', '&fmno='.$this->getUserFmno($user_id), $download_url);
                }
                $file_details = $this->GetKnowFileDetails($node->id);
              // Fetch size of KNOW document ID
            } else {
                if ($doc_type == 'video') {
                    $download_url = ($node->video_fid > 0) ? SITE_PATH . '/download/file/' . $node->video_fid : '';
                    $file_details = $this->GetFileDetails($node->video_fid);
                } else {
                    $download_url = ($node->download_fid > 0) ? SITE_PATH . '/download/file/' . $node->download_fid : '';
                    $file_details = $this->GetFileDetails($node->download_fid);
                }
            }

            // Disable downalod
            if (isset($node->disable_download) && $node->disable_download == 1) {
                $download_url = '';
            }

            //iframe_url
            $preview_url = "";
            if ($node->hasknowpreview == 1) {
                $preview_url = str_replace(':know_id', $node->know_id, $preview);
                $preview_url = str_replace(':chargeCode', $charge_code, $preview_url);
                $preview_url = str_replace(':asset_type', $asset_type, $preview_url);
                $keystring = getVariable('know_downlod_preview_new_old_api');
                if($keystring == 'new') {
                  $preview_url = str_replace(':FMNO', '&fmno='.$this->getUserCryptFmno($user_id), $preview_url);
                } else {
                  $preview_url = str_replace(':FMNO', '&fmno='.$this->getUserFmno($user_id), $preview_url);
                }
            } else {
                if ($doc_type == 'video') {
                    $temp = $this->getFilepathFromFid($node->video_fid);
                    $preview_url = urldecode($temp);
                } else {
                    $temp = $this->getFilepathFromFid($node->preview_id);
                    $preview_url = urldecode($temp);
                }
            }

            if (isset($node->do_not_display_preview) && $node->do_not_display_preview ==1) {
                $preview_url = "";
            }

            $data['id'] = $node->id;
            $data['title'] = !empty($node->title) ? $node->title : '';
            // Get document type
            $data['document_type'] = $doc_type;
            // Get document category
            $data['category'] = (isset($node->category) && $node->category > 0) ? $this->getTermName($node->category) : "";
            if ($node->know_id > 0 && isset($node->resource_label_know) && !empty($node->resource_label_know)) {
                $data['category'] = $node->resource_label_know;
            }
            
            if (isset($node->is_qa) && $node->is_qa == 1) {
                $data['document_type'] = 'qa';
                $data['category'] = 'Q&A';
            }
            $data['summary'] = $node->summary;
            $data['text'] = $node->text;
            $data['download_link'] = $download_url;
            $data['file_type'] = isset($file_details['type']) ? $file_details['type'] : '';
            $data['file_size'] = isset($file_details['filesize']) ? $file_details['filesize'] : '';
            $data['iframe_url'] = $preview_url;
            $data['bookmarked'] = $this->isBookmarkedNode($eng_id, $node->id, null, DOCUMENT);
            $data['is_secondary'] = isset($node->is_secondary) ? $node->is_secondary : 0;
            $data['is_qa'] = isset($node->is_qa) ? $node->is_qa : 0;
            $data['trophy'] = ($node->trophy === 1) ? true : false;
            $data['external_url'] = isset($node->ext_url) ? $node->ext_url : '';
            $data['asset_type'] = $asset_type;

            $data['is_podcast_webcast'] = 0;
            if (isset($node->is_webcasts_podcasts) &&  $node->is_webcasts_podcasts === 1) {
                $data['is_podcast_webcast'] = 1;
                $data['document_type']  = 'video';
                $data['category']  = isset($node->podcast_webcast_category) && $node->podcast_webcast_category > 0 ? $this->getTermName($node->podcast_webcast_category) : '';
                $data['date'] = date('F d, Y', strtotime($node->podcast_webcast_date));
                $data['text'] = isset($node->podcast_webcast_host_name) ? $node->podcast_webcast_host_name : '';
                if(isset($node->is_external_pw) && $node->is_external_pw === 1) {
                    $data['external_url'] = isset($node->hosted_link_pw) ? $node->hosted_link_pw : '';
                } else {
                    $data['external_url'] = isset($node->podcast_webcast_audio_video) ? $this->getFilepathFromFid($node->podcast_webcast_audio_video) : '';
                }
            }
        }
        return $data;
    }

   /**
   * Get Resource Documents by node id
   * @param int $nid (Node Id)
   * @param int $eng_id (Engagement Id)
   * @return array $data
   */
    public function getResourceDocumentsByNidSolr($nids = [], $user_id = 0)
    {
        $data = [];
        $resource_index = env('MATERIALS_SOLR_INDEX', 'resource');

        if (is_array($nids) && !empty($nids)) {
            $client =  \App\Util\StrategySolr::getInstance();
        // get a select query instance
            $query = $client->createSelect();
            $query->addFilterQuery(array('key'=>'index_id', 'query'=>'index_id:'.$resource_index));
            $query->addFilterQuery(array('key'=>'is_status', 'query'=>'is_status:1'));
            $nid = implode(' OR ', $nids);
            $query->addFilterQuery(array('key'=>'is_nid', 'query'=>'is_nid:('.$nid.')'));

            $display_fields = ['is_nid',
             'tm_title',
             'ss_type',
             'ss_field_resource_label$name',
             'ss_scfp_document_type',
             'tm_body$value',
             'tm_body$summary',
             'ss_scfp_download_url',
             'ss_scfp_file_type',
             'ss_scfp_file_size',
             'ss_field_external_url$url',
             'ss_scfp_iframe_url',
             'ss_field_asset_type',
             'is_field_know_id',
             'is_field_disable_download',
             'is_field_is_webcasts_podcasts',
             'is_field_podcast_webcast_category',
             'ds_field_date_of_the_podcast_webcas',
             'tm_field_host_name',
             'tm_field_hosted_link_pw$title',
             'ss_field_hosted_link_pw$url',
             'tm_field_podcast_webcast_category$name',
             'is_field_is_external_pw',
             'is_field_audio_video_upload$file',
             'tm_field_resource_label_know',
            ];
            $query->setFields($display_fields);
        // this executes the query and returns the result
            $query->setStart(0)->setRows(10000);
            $resultset = $client->select($query);
            foreach ($resultset as $document) {
                $data[] = $this->getMaterialSearchParseSolrData($document, $user_id);
            }
        }
        return $data;
    }

     /**
     * Get VideoDetails by node id
     * @param int $nid (Node Id)
     * @return array $data
     */
    public function getVideoDetailsByNid($nid)
    {
        $data = null;
        $nodes = DB::table('node as n')
        ->where('n.nid', $nid)
        ->where('n.status', self::PUBLISHED)
        ->leftJoin('field_data_field_video_thumbnail as t', 't.entity_id', '=', 'n.nid')
        ->leftJoin('field_data_field_video as v', 'v.entity_id', '=', 'n.nid')
        ->leftJoin('field_data_field_is_secondary_resource as s', 's.entity_id', '=', 'n.nid')
        ->leftJoin('field_data_field_is_q_a as qa', function ($join) {
            $join->on('qa.entity_id', '=', 'n.nid')
            ->where('qa.field_is_q_a_value', '=', 0);
        })
        ->where('s.field_is_secondary_resource_value', 0)
        ->select(array('n.nid as id',
        'n.title as title',
        'v.field_video_fid as video',
        't.field_video_thumbnail_fid as thumbnail'
        ))
        ->distinct()
        ->get();
        if ($nodes) {
            $node = $nodes['0'];
           //title
            $data['title'] = $node->title;

           //video
            $vid_src = "";
            $vid_src = $this->getFilepathFromFid($node->video);
            $data['video_src'] = $vid_src;

           //thumbnail
             $vid_thumb = "";
             $vid_thumb = $this->getFilepathFromFid($node->thumbnail);
             $data['video_poster_src'] = $vid_thumb;
        }
            return $data;
    }

      /**
       * stripTags funtctio to strip html tags
       * @param  String $str
       * @return String
       */
    public function stripTags($str)
    {
        return html_entity_decode(str_replace('&nbsp;', ' ', strip_tags($str)));
    }
    /**
     * html_entity_decode function to to decode html entity
     * @param  String $str
     * @return String
     */
    public function htmlDecode($str)
    {
        return html_entity_decode($str);
    }

     /**
     * Check node is particuler type of content
     * @param Int $nid Node id  to check content tyep
     * @param String  $content_type Content type
     *
     * @return boolean
     */
    public function isContentType($nid, $content_type)
    {
        return (bool) DB::table("node")
        ->where('type', $content_type)
        ->where('nid', $nid)
        ->value('nid');
    }

     /**
     * Function to Bookmark Node
     * @param Int $engagement_id Engagement id
     * @param Int $nid Entity id
     * @param Int $user_id User id
     * @param Int $type
     *
     * @return boolean
     */
    public function isBookmarkedNode($engagement_id, $nid, $user_id = 0, $type = null)
    {
        $bookmarked = (bool)DB::table('scf_bookmark as b')
        ->where('b.nid', $nid)
        ->where('b.eid', $engagement_id)
        //->where('b.uid',$user_id) // For Future use if bookmark needed user scpecific
        ->where('b.type', $type)
        ->limit(1)
        ->value('b.nid');

        return $bookmarked;
    }

     /**
     * Function to Get all Bookmarked Nodes
     * @param Int $engagement_id Engagement id
     * @param Array $type
     * @param Array $nid
     *
     * @return array
     */
    public function getAllBookmarkedNodes($engagement_id, $type = [], $nid = [])
    {
        $bookmarked = [];
        if (!empty($nid)) {
            $bookmarks = DB::table('scf_bookmark as b')
            ->join('node as n', 'n.nid', '=', 'b.nid')
            ->where('b.eid', $engagement_id)
            ->whereIn('b.type', $type)
            ->whereIn('b.nid', $nid)
            ->where('n.status', self::PUBLISHED)
            ->get(['b.nid']);
        } else {
            $bookmarks = DB::table('scf_bookmark as b')
            ->join('node as n', 'n.nid', '=', 'b.nid')
            ->where('b.eid', $engagement_id)
            ->whereIn('b.type', $type)
            ->where('n.status', self::PUBLISHED)
            ->get(['b.nid']);
        }
        foreach ($bookmarks as $bookmark) {
            $bookmarked[$bookmark->nid] = $bookmark->nid;
        }
        return $bookmarked;
    }

     /**
      * Check engagement is valid for user
      * valid engaggement checking means below criteria
      * 1. Engagement must be published
      * 2. Engagement must be default
      *
      * @param Int $engagement_nid Engagement nid
      * @param Int $uid Engagement User uid
      *
      * @return Boolean
      */
    public function isValidUserEngagement($engagement_nid, $uid)
    {
        return (bool)DB::table('engagement_user as eu')
        ->join('node as n', 'n.nid', '=', 'eu.eid')
        ->join('users as u', 'u.uid', '=', 'eu.uid')
        ->where(function ($query) use ($uid) {
                 //Is user is invited or creater of node
                 $query->where('eu.uid', $uid)
                       ->orWhere('n.uid', $uid);
        })
        ->where('n.nid', $engagement_nid)
        ->where('eu.is_default', self::IS_DEAFULT)
        ->where('n.status', self::PUBLISHED)
        ->limit(1)
        ->value('eu.eid');
    }

      /**
      * Get true/false value for bookamrk, recommend, read apis
      * @param Int/String $val
      *
      * @return String true/false/invalid
      */
    public function getBoolValue($val)
    {
        $value = strtolower(trim($val));
        if ($value === 'true' || $value === true || $value === 1 || $value === '1') {
            return 'true';
        }

        if ($value === 'false' || $value === false || $value === 0 || $value === '0' || $value == '') {
            return 'false';
        }
        return 'invalid';
    }
      /**
       * getDocumentType
       * @param  Integer $nid Node Id
       * @return String  single, multi, tools, video, learning
       */
    public function getDocumentType($nid, $is_child)
    {
        $type = 'single';
        $isMulti = $this->isMultiDocument($nid);
        $resource_type_tid = $this->getFieldValue('field_resource_type', $nid, 'node', 'tid');
        $resource_type_uuid = $this->getTermUUID($resource_type_tid);
            switch ($resource_type_uuid) {
                case '2ca59b7c-f2f3-4911-9324-c82136f53ada':
                    //Documents
                    $type = 'single';
                    if ($isMulti) {
                        $type = 'multi';
                    }
                    break;
                case 'a3231992-eb3a-478d-ac9c-1da61bc40a99':
                    //Videos
                    $type = 'video';
                    if ($isMulti) {
                        $type = 'multi_videos';
                    }
                    break;
                case 'dfddd4bd-0f68-4b50-975b-f05f396f44b7':
                    //tools
                    $type = 'tools';
                    if ($isMulti) {
                        $type = 'multi_tools';
                    }
                    break;
                case 'f8d0d4b6-9be9-4535-a043-fd18330e2949':
                    //Learning module
                    $type = 'learning';
                    break;
            }
        return $type;
    }

      /**
       * isMultiDocument check multi document
       * @param  @param  Integer $nid Node Id
       * @return boolean
       */
    public function isMultiDocument($nid)
    {
        return (bool)$this->getMultiFieldValue('field_multidocument_resource', $nid, 'node', 'target_id');
    }

      /**
       * getArticleData
       * @param  int $nid node id
       * @param  int $engagement_id Enagagement id
       * @return Array $response
       */
    public function getArticleData($nid, $engagement_id)
    {
        $response = [];
        $nodes = DB::table('node as n')
            ->where('n.type', NEWS_AND_ARTICLE_CONTENT_TYPE)
            ->where('n.status', self::PUBLISHED)
            ->where('n.nid', $nid)
            ->leftJoin('field_data_field_feed_image_link as il', 'il.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_feed_url_link as fl', 'fl.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_feed_news_source as fn', 'fn.entity_id', '=', 'n.nid')
            ->leftJoin('field_data_field_article_upload_file as up', 'up.entity_id', '=', 'n.nid')
            ->limit(1)
            ->get(array('n.nid as nid',
            'n.title as title',
            'il.field_feed_image_link_url as imagelink',
            'fl.field_feed_url_link_url as feedlink',
            'fn.field_feed_news_source_tid as newssource',
            'up.field_article_upload_file_fid as upload_file_fid'));
        foreach ($nodes as $key => $node) {
            $response['id'] = $node->nid;
            $response['title'] = $node->title;
            $response['document_type'] = "";

            $uuid = $this->getTermUUID($node->newssource);
            $news_source = 'McKinsey Insights';
            $default_img = SITE_PATH .'/sites/all/themes/scfp/images/mckinsey_quarterly.jpg';
            //Harvard Business Review
            if ($uuid == '18407446-7828-463d-aa14-ed77df0e1f5b') {
                $default_img = SITE_PATH .'/sites/all/themes/scfp/images/harverd_1.jpg';
                $news_source = 'Harvard Business Review';
            }
            //Mckinsey Insights
            if ($uuid == '1e94bb9b-d371-47e9-9139-eee1f9a91711') {
                $default_img = SITE_PATH .'/sites/all/themes/scfp/images/mckinsey_quarterly.jpg';
            }
            //McKinsey Quarterly
            if ($uuid == 'bc408e5e-7751-4093-9b46-016d6adda612') {
                $default_img = SITE_PATH .'/sites/all/themes/scfp/images/mckinsey_quarterly.jpg';
            }

            $download_path = $node->feedlink;
            if (empty($node->feedlink) && isset($node->upload_file_fid) && !empty($node->upload_file_fid)) {
                $download_path = $this->getFilepathFromFid($node->upload_file_fid);
            }
            $response['download_link'] = $download_path;
            $response['iframe_url'] = !empty($node->imagelink) ? $node->imagelink : $default_img;
            $response['bookmarked'] = $this->isBookmarkedNode($engagement_id, $node->nid, null, ARTICLE);
            $response['category'] = $news_source;
        }
        return $response;
    }

      /**
      * Check External Expert
      * @param Int $nid Node id
      *
      * @return boolean
      */
    public function isExternalExpert($nid)
    {
        return (bool) DB::table('node as n')
         ->join('field_data_field_is_external_expert as ex', 'ex.entity_id', '=', 'n.nid')
         ->where('n.status', self::PUBLISHED)
         ->where('n.type', EXPERT_CONTENT_TYPE)
         ->where('n.nid', $nid)
         ->value('ex.field_is_external_expert_value');
    }

      /**
      * Check email contact
      * @param Int $nid Node id
      *
      * @return boolean
      */
    public function isEmailContact($nid)
    {
        return (bool) DB::table('node as n')
         ->join('field_data_field_is_email_contact as ex', 'ex.entity_id', '=', 'n.nid')
         ->where('n.status', self::PUBLISHED)
         ->where('n.type', EXPERT_CONTENT_TYPE)
         ->where('n.nid', $nid)
         ->value('ex.field_is_email_contact_value');
    }


    public function getTermId($term_name)
    {
        $term_id = DB::table('taxonomy_term_data as t')
        ->where('name', $term_name)
        ->get(['tid as tid']);
        $term_id = reset($term_id);
        return $term_id->tid;
    }

    public function getFactivaIndustryCode($term_id)
    {
        $factiva = [];
        $id = DB::table('field_data_field_factiva_industry as f')
            ->Join('field_data_field_factiva_id as i', 'i.entity_id', '=', 'f.field_factiva_industry_value')
            ->where('f.entity_id', $term_id)
            ->get(['field_factiva_id_value as factiva_id']);
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $temp = '';
                $temp = strtoupper(trim($value->factiva_id));
                if (!in_array($temp, $factiva)) {
                    $factiva[] = $temp;
                }
            }
        }
        return $factiva;
    }

    /**
     * getUsername
     * @param  Int $uid
     * @return String
     */
    public function getUsername($uid)
    {
        return DB::table('users as u')
          ->where('u.uid', $uid)
          ->value('u.name');
    }

  /**
   * getUserProfile
   * @param  Int $uid
   * @return String
   */
    public function getUserProfile($uid = 0)
    {
        $default_pic = SITE_PATH .'/sites/all/themes/scfp/images/userpic.jpg';
        $path = '';
        $path = $this->getFieldValue('field_user_profile_image', $uid, 'user', $data = 'value');
        $path = $this->replaceHTTPwithHTTPS($path);
        if (empty($path)) {
            return $default_pic;
        } else {
            $is_exist = $this->is_url_exist($path);
            if ($is_exist) {
                return $path;
            } else {
                return $default_pic;
            }
        }
    }

  /**
   * is_url_exist
   * @param  String  $url
   * @return boolean
   */
    public function is_url_exist($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

   /**
    * fetchWhoToContactDetails
    * @param  Int $nid
    * @return Array
    */
    public function fetchWhoToContactDetails($nid)
    {
        $data = [];
        $author_contact_data = [];
        $authors =  DB::table('field_data_field_who_to_contact as a')
        ->where('a.entity_id', $nid)
        ->where('a.bundle', 'resource')
        ->select(array('a.field_who_to_contact_target_id as author_id',
        ))
        ->orderBy('a.delta', 'asc')
        ->get();
        foreach ($authors as $val) {
            $temp = $this->getAuthorById($val->author_id, 3);
            if(!empty($temp)) {
                $data[] =  $temp;
            }
        }
        return $data;
    }

   /**
    * Function to return Expert Industry
    * @param Int $entity_id Entity id
    *
    * @return
    */
    public function getExpertIndustry($expert_id, $limit = 3)
    {
        $industries = [];
        $industry_data = [];

        $cache = getCache($expert_id, SCFP_EXPERT_API_DATA);
        if ($cache) {
            $data = $cache;
        } else {
            $data = DB::table('scf_experts_mapping')
                 ->where('nid', $expert_id)
                 ->value('apidata');
            setForeverCache($expert_id, $data, SCFP_EXPERT_API_DATA);
        }

        if (isset($data) && !empty($data)) {
            $temp = unserialize($data);
            $industries = $temp['industry'];
            $i = 0;
            foreach ($industries as $ind) {
                if ($i < $limit) {
                    $industry_data[] = [
                    'title' => $this->getTermName($ind['name']),
                    'percentage' => ceil($ind['percentage']),
                    ];
                }
                $i++;
            }
        }
        return $industry_data;
    }

   /**
    * Function to return Expert Function
    * @param Int $entity_id Entity id
    *
    * @return
    */
    public function getExpertFunction($expert_id, $limit = 3)
    {
        $functions = [];
        $function_data = [];

        $cache = getCache($expert_id, SCFP_EXPERT_API_DATA);
        if ($cache) {
            $data = $cache;
        } else {
            $data = DB::table('scf_experts_mapping')
               ->where('nid', $expert_id)
               ->value('apidata');
            setForeverCache($expert_id, $data, SCFP_EXPERT_API_DATA);
        }

        if (isset($data) && !empty($data)) {
             $temp = unserialize($data);
             $functions = $temp['functions'];
             $i = 0;
            foreach ($functions as $func) {
                if ($i < $limit) {
                    $function_data[] = [
                       'title' => $func['name'],
                       'percentage' => ceil($func['percentage']),
                    ];
                }
                $i++;
            }
        }
        return $function_data;
    }

   /**
    * Function to return Expert Geography
    * @param Int $entity_id Entity id
    *
    * @return
    */
    public function getExpertGeography($expert_id)
    {
        $geographies = [];
        $geography_data = [];
        $geo_cache = getCache($expert_id, SCFP_EXPERT_GEOGRAPHY_DATA);
        if ($geo_cache) {
            return unserialize($geo_cache);
        } else {
            $cache = getCache($expert_id, SCFP_EXPERT_API_DATA);
            if ($cache) {
                $data = $cache;
            } else {
                $data = DB::table('scf_experts_mapping')
                 ->where('nid', $expert_id)
                 ->value('apidata');
                setForeverCache($expert_id, $data, SCFP_EXPERT_API_DATA);
            }

            if (isset($data) && !empty($data)) {
                $temp = unserialize($data);
                $geographies = $temp['geography'];
                foreach ($geographies as $geo) {
                    $geography_data[] = [
                    'title' => $this->getTermName($geo['name']),
                    'percentage' => ceil($geo['percentage']),
                    ];
                }
                setForeverCache($expert_id, serialize($geography_data), SCFP_EXPERT_GEOGRAPHY_DATA);
            }
        }

        return $geography_data;
    }

   /**
    * Function to return Expert contact
    * @param Int $entity_id Entity id
    *
    * @return
    */
    public function getExpertContact($expert_id)
    {
        $contact = [];
        $contact_data = [];
        $cnt_cache = getCache($expert_id, SCFP_EXPERT_CONTACT_DATA);
        if ($cnt_cache) {
            return unserialize($cnt_cache);
        } else {
             $cache = getCache($expert_id, SCFP_EXPERT_API_DATA);
            if ($cache) {
                $data = $cache;
            } else {
                $data = DB::table('scf_experts_mapping')
                 ->where('nid', $expert_id)
                 ->value('apidata');
                setForeverCache($expert_id, $data, SCFP_EXPERT_API_DATA);
            }

            if (isset($data) && !empty($data)) {
                $temp = unserialize($data);
                $contact = $temp['contact'];
                foreach ($contact as $cnt) {
                    $contact_data[] = [
                    'label' => $cnt['label'],
                    'text' => $cnt['value'],
                    ];
                }
                setForeverCache($expert_id, serialize($contact_data), SCFP_EXPERT_CONTACT_DATA);
            }
        }
        return $contact_data;
    }

   /**
    * Function to return Expert Assistant
    * @param Int $entity_id Entity id
    *
    * @return
    */
    public function getExpertAssistant($expert_id)
    {
        $assistant = [];
        $assistant_data = [];
        $ast_cache = getCache($expert_id, SCFP_EXPERT_ASSISTANT_DATA);
        if ($ast_cache) {
            return unserialize($ast_cache);
        } else {
            $cache = getCache($expert_id, SCFP_EXPERT_API_DATA);
            if ($cache) {
                 $data = $cache;
            } else {
                $data = DB::table('scf_experts_mapping')
                ->where('nid', $expert_id)
                ->value('apidata');
                setForeverCache($expert_id, $data, SCFP_EXPERT_API_DATA);
            }
            if (isset($data) && !empty($data)) {
                $temp = unserialize($data);
                $assistant = $temp['assistant'];
                foreach ($assistant as $ast) {
                    $assistant_data[] = [
                    'label' => $ast['label'],
                    'text' => $ast['value'],
                    ];
                }
                setForeverCache($expert_id, serialize($assistant_data), SCFP_EXPERT_ASSISTANT_DATA);
            }
        }
        return $assistant_data;
    }

   /**
    * fetchWhoToContactDetails
    * @param  Int $nid
    * @return Array
    */
    public function GetFileDetails($fid)
    {
        $response = [];
        $query =  DB::table('file_managed as f')
        ->where('f.fid', $fid)
        ->get(['f.filemime as type','f.filesize as filesize', 'f.filename as name']);
        if (is_array($query) && !empty($query)) {
            $query = reset($query);
            $type = "";
            $temp = explode(".", $query->name);
            $type = end($temp);
            $response['type'] = $type;
            $response['filesize'] = isset($query->filesize) ? ceil($query->filesize / 1048576). ' MB' : '';
        }
        return $response;
    }

    /**
     * fetchWhoToContactDetails
     * @param  Int $nid
     * @return Array
     */
    public function GetKnowFileDetails($nid)
    {
        $response = [];
        $query = DB::table('field_data_field_file_size as ks')
        ->join('field_data_field_file_type as kt', 'kt.entity_id', '=', 'ks.entity_id')
        ->where('ks.entity_id', $nid)
        ->get(array('field_file_size_value as filesize', 'field_file_type_value as type'));
        if (is_array($query) && !empty($query)) {
            $query = reset($query);
            $type = "";
            $temp = explode(".", $query->type);
            if (is_array($temp)) {
                $type = end($temp);
            } else {
                $type = $query->type;
            }
            $response['type'] = $type;
            $response['filesize'] = isset($query->filesize) ? ceil($query->filesize / 1048576). ' MB' : '';
        }
        return $response;
    }

    /**
     * Get tracking code and add into the URL.
     * @param String $field_feed_link
     * @param String $tracking_code
     * @return String $updated_url
     */
    public function getURLwithTrackingCode($field_feed_link, $tracking_code)
    {
        if ($field_feed_link) {
            $tracking_code = $this->getVariable($tracking_code);
            if ($tracking_code) {
                if (strrpos($field_feed_link, '?')) {
                    return $field_feed_link . '&' . $tracking_code;
                } else {
                    return $field_feed_link . '?' . $tracking_code;
                }
            }
        }
        // Returns untracked URL.
        return $field_feed_link;
    }

     /**
      * Function to return Parse Expert data
      * @param String $data
      *
      * @return
      */
    public function getParsedExpertDataSolr($data, $limit = 'All')
    {
        $parse_data = [];
        if (isset($data) && !empty($data)) {
            $temp = unserialize($data);
            if (is_array($temp)) {
                $parse_data =  ($limit == 'All') ? $temp : array_slice($temp, 0, $limit, true);
            }
        }
        return $parse_data;
    }

    public function getMaterialSearchParseSolrData($document = [], $uid = 0 )
    {
        $documents = [];
        $display_fields = ['is_nid' => 'id',
        'tm_title' => 'title',
        'ss_type' => 'type',
        'ss_field_resource_label$name' => 'category',
        'ss_scfp_document_type' => 'document_type',
        'tm_body$value' => 'text',
        'ss_scfp_download_url' => 'download_link',
        'ss_scfp_file_type' => 'file_type',
        'ss_scfp_file_size' => 'file_size',
        'ss_field_external_url$url' => 'external_url',
        'ss_scfp_iframe_url' => 'iframe_url',
        'ss_field_asset_type' => 'asset_type',
        'is_field_disable_download' => 'disable_download',
        ];

        $display_fields_news = ['is_nid' => 'id',
        'tm_title' => 'title',
        ];


        $temp = [];
        foreach ($document as $field => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $temp[$field] = $value;
        }

        if ($temp['ss_type'] === RESOURCES_CONTENT_TYPE) {
            foreach ($display_fields as $key => $f) {
                $documents[$f] = isset($temp[$key]) && !empty($temp[$key]) ? $temp[$key] : '';
    
                if ($f == 'text') {
                    $body = '';
                    $body = isset($temp[$key]) && !empty($temp[$key]) ? $this->stripTags($temp[$key]) : '';
                    $summary = '';
                    $summary = isset($temp['tm_body$summary']) && !empty($temp['tm_body$summary']) ? $this->stripTags($temp['tm_body$summary']) : '';
                    $documents[$f] = !empty($summary) ? $summary : $body;
                }
                if ($f == 'iframe_url') {
                    $documents[$f] = isset($temp[$key]) && !empty($temp[$key]) ? urldecode($temp[$key]) : '';
                    $keystring = getVariable('know_downlod_preview_new_old_api');
                    if($keystring == 'new') {
                      $documents[$f] = str_replace(':FMNO', '&fmno='.$this->getUserCryptFmno($uid), $documents[$f]);
                    } else {
                      $documents[$f] = str_replace(':FMNO', '&fmno='.$this->getUserFmno($uid), $documents[$f]);
                    }
                }
                if ($f == 'download_link') {
                    $documents[$f] = isset($temp[$key]) && !empty($temp[$key]) ? urldecode($temp[$key]) : '';
                    $keystring = getVariable('know_downlod_preview_new_old_api');
                    if($keystring == 'new') {
                      $documents[$f] = str_replace(':FMNO', '&fmno='.$this->getUserCryptFmno($uid), $documents[$f]); 
                    } else {
                      $documents[$f] = str_replace(':FMNO', '&fmno='.$this->getUserFmno($uid), $documents[$f]); 
                    }
                }
                if ($f == 'external_url') {
                    $documents[$f] = isset($temp[$key]) && !empty($temp[$key]) ? urldecode($temp[$key]) : '';
                }
                if ($f == 'asset_type') {
                    $documents[$f] = isset($temp[$key]) && !empty($temp[$key]) ? $temp[$key] : 'document';
                }

                $disable_download = 0;
                if ($f == 'disable_download') {
                    $disable_download = isset($temp[$key]) && !empty($temp[$key]) ? $temp[$key] : 0;
                }
                if (isset($disable_download) && $disable_download == 1) {
                    $documents['download_link'] = '';
                }

                if ($f == 'category') {
                    if(isset($temp['tm_field_resource_label_know']) && !empty($temp['tm_field_resource_label_know'])) {
                        $documents[$f] =  $temp['tm_field_resource_label_know'];
                    }
                }
                
            }
            $documents['is_podcast_webcast'] = 0;
            if (isset($temp['is_field_is_webcasts_podcasts']) && $temp['is_field_is_webcasts_podcasts'] === 1) {
                $documents['is_podcast_webcast'] = 1;
                $documents['document_type']  = 'video';
                $documents['category']  =  isset($temp['tm_field_podcast_webcast_category$name'])  ?  $temp['tm_field_podcast_webcast_category$name']  : '';
                if(isset($temp['ds_field_date_of_the_podcast_webcas'])) {
                    $documents['date'] = date('F d, Y', strtotime($temp['ds_field_date_of_the_podcast_webcas']));
                } else  {
                    $documents['date'] = '';
                }
                $documents['text'] = isset($temp['tm_field_host_name']) ? $temp['tm_field_host_name'] : '';
                if(isset($temp['is_field_is_external_pw']) && $temp['is_field_is_external_pw'] === 1) {
                    $documents['external_url'] = isset($temp['ss_field_hosted_link_pw$url']) ? $temp['ss_field_hosted_link_pw$url'] : '';
                } else {
                    $documents['external_url'] = isset($temp['is_field_audio_video_upload$file']) ? $this->getFilepathFromFid($temp['is_field_audio_video_upload$file']) : '';
                }
            }
        }

        if ($temp['ss_type'] === NEWS_AND_ARTICLE_CONTENT_TYPE) {
            $news_source = 'McKinsey Insights';
            $default_img = SITE_PATH .'/sites/all/themes/scfp/images/mckinsey_quarterly.jpg';
          //Harvard Business Review
            if (isset($temp['ss_field_feed_news_source$uuid'])  && $temp['ss_field_feed_news_source$uuid'] == '18407446-7828-463d-aa14-ed77df0e1f5b') {
                $default_img = SITE_PATH .'/sites/all/themes/scfp/images/harverd_1.jpg';
                $news_source = 'Harvard Business Review';
            }

          //Mckinsey Insights
            if (isset($temp['ss_field_feed_news_source$uuid'])  && $temp['ss_field_feed_news_source$uuid'] == '1e94bb9b-d371-47e9-9139-eee1f9a91711') {
                $default_img = SITE_PATH .'/sites/all/themes/scfp/images/mckinsey_quarterly.jpg';
            }
          //McKinsey Quarterly
            if (isset($temp['ss_field_feed_news_source$uuid'])  && $temp['ss_field_feed_news_source$uuid'] == 'bc408e5e-7751-4093-9b46-016d6adda612') {
                $default_img = SITE_PATH .'/sites/all/themes/scfp/images/mckinsey_quarterly.jpg';
            }
            $download_path = '';
            $download_path = isset($temp['ss_field_feed_url_link$url']) && !empty($temp['ss_field_feed_url_link$url']) ? urldecode($temp['ss_field_feed_url_link$url']) : '';
            if (empty($download_path) && isset($temp['ss_field_article_upload_file$file$url']) && !empty($temp['ss_field_article_upload_file$file$url'])) {
                $download_path = $temp['ss_field_article_upload_file$file$url'];
            }

            $iframe_url = '';
            if (isset($temp['ss_field_feed_image_link$url']) && !empty($temp['ss_field_feed_image_link$url'])) {
                $iframe_url = urldecode($temp['ss_field_feed_image_link$url']);
            }

            foreach ($display_fields_news as $key => $f) {
                $documents[$f] = isset($temp[$key]) && !empty($temp[$key]) ? $temp[$key] : '';
            }

            $documents['type'] = ARTICLE;
            $documents['download_link'] = $download_path;
            $documents['iframe_url'] = !empty($iframe_url) ? $iframe_url : $default_img;
            $documents['category'] = $news_source;
            $documents['document_type'] = "";
        }
        return $documents;
    }

    /**
     * getDocumentsData
     * used in journey docs api, tools api
     * @param  integer $nid
     * @return array
     */
    public function getDocumentsData($nid = 0, $user_id = 0)
    {
        $data = [];
        $doc_data = $this->getResourceDocumentsByNidSolr([$nid], $user_id);
        if (!empty($doc_data)) {
            $doc_data = @$doc_data['0'];
            $data = $doc_data;
        }
        return $data;
    }

    /**
     * getDetailsfromTid
     * @param  integer $tid
     * @return object
     */
    public function getDetailsfromTid($tid)
    {
        $cache = getCache($tid, SCFP_TERMS_DETAILS_FROM_TID);
        if ($cache) {
            return unserialize($cache);
        } else {
            $result = DB::table('taxonomy_term_data as t')
            ->where('tid', $tid)
            ->select([
            't.name as name',
            't.uuid as uuid',
            't.description as description'
            ])
            ->get();
            $result = reset($result);
            setForeverCache($tid, serialize($result), SCFP_TERMS_DETAILS_FROM_TID);
            return $result;
        }
    }

    /**
     * replaceHTTPwithHTTPS
     * @param  string $url
     * @return string
     */
    public function replaceHTTPwithHTTPS($url = "")
    {
        return $url = str_replace('http://', 'https://', $url);
    }

    /**
     * Get Experts topics
     * @param String $str
     *  Comma sperated topics id
     * @return Array
     */
    public function getExpertTopics($str = '')
    {
        $topics = [];
        $excludes = $this->getExcludedTopics();
        if (!empty($str)) {
            $topics_ids = explode(',', $str);
            foreach ($topics_ids as $topics_id) {
                if(!in_array($topics_id, $excludes)) {
                 $topics[] = ['id' => $topics_id, 'name' => $this->getTermName($topics_id)];
                }
            }
        }
        return $topics;
    }

    public function getExcludedTopics() {
        $exclude = [63];
        $topics_vid = $this->getVidfromVocabMachineName('topics');
        $terms = DB::table('taxonomy_term_data as t')
            ->leftJoin('field_data_field_exclude_display as ex', 'ex.entity_id', '=', 't.tid')
            ->where('ex.field_exclude_display_value', '=', 1)
            ->where('vid', $topics_vid)
            ->select(['t.tid as id'])
            ->get();
            foreach($terms as $term) {
                $exclude[] = $term->id;
            }
        return $exclude;
    }

  /**
   * get User FMNO
   * @param  Integer $user_id
   * @return String
   */
  public function getUserFmno($user_id)
  {
    $fmno = '';
    $fmno = $this->getFieldValue('field_user_fmno', $user_id, 'user', $data = 'value');
    if(empty($fmno)) {
      $fmno = isset($_SERVER['HTTP_FMNO']) ? $_SERVER['HTTP_FMNO'] : '';
    }
    if(!empty($fmno)) {
        $fmno = str_pad($fmno,5,0,STR_PAD_LEFT);
    }
    return $fmno;
    }

    public function getUserCryptFmno($user_id)
    {
        $fmno = $this->getUserFmno($user_id);
        if(!empty($fmno)) {
            $fmno = str_pad($fmno,6,0,STR_PAD_LEFT);
        }
        return cryptFmno($fmno);
    }
} -->
