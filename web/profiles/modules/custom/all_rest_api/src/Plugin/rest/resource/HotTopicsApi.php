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
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\all_rest_api\CustomServices;
use \Drupal\node\Entity\Node;
use \Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "get-hot-topics",
 *   label = @Translation("Get Hot Topics  API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-hot-topics"
 *   }
 * )
 */


class HotTopicsApi extends ResourceBase {

  /**
   * The node data.
   *
   * @var Drupal\all_rest_api\CustomService
  */
  // protected $nodedata;

  /**
   * ExampleHotTopicsApi constructor.
   *
   * @param Drupal\all_rest_api\CustomService $node_data
   *   The form builder.
  */
  // public function __construct(FormBuilder $form_builder, $node_data) {
  //   $this->nodeData  = $node_data;
  // }

  // /**
  //  * {@inheritdoc}
  //  *
  //  * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
  //  *   The Drupal service container.
  //  *
  //  * @return static
  //  */

  // public static function create(ContainerInterface $container) {
  //   return new static(
  //     $container->get('all_rest_api.all_services')
  //   );
  // }

  public function get() {

  //   $service = \Drupal::service('all_reat_api.customservice');    
  //   $data =  $service->get_number();

  // //   // $data = $this->AllService->getData();

  //  dd($data);

  //  $servicetesting = \Drupal::service('all_reat_api.customservice');

  //   $data = $servicetesting->getData();

  //   dd($data);

  //   $maindata = $servicetesting->setData();

  //   dd($maindata);
  //   dd($data);
  
 
//  $val = $user;
//  dd($val);



    $response = [];

    // $response['items'] = $this->getResourceDocumentsByNid();
      $response['items'] = $this->getHotTopics($request, $user);
      $meta = ['info_text'=> 'Hot topics'];

       $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  
      $results = new ResourceResponse($data);
       return $results;

  }



/**
  * Get Hot topics
  * @return Array $response
  */
  public function getHotTopics($request, $user){
      // public function getHotTopics(){

        // $service = \Drupal::service('all_reat_api.all_services');
        // $service->setData();
        // // dd($service);



        // dd('xkjnacb');

      $response = [];
      // $limit = ($request->query('limit') && $request->query('limit') > 0) ? $request->query('limit') : 10;
      // $offset = ($request->query('offset') && $request->query('offset') >= 0) ? $request->query('offset') : 0;
      // Get hot topics documents/articles
       $nodes = \Drupal::database()->select('scf_hot_topics_ordering', 'b');
       $nodes->join('node_field_data', 'n', 'b.nid = n.nid');
       $nodes->leftJoin('node__field_external_url', 'eu', 'eu.entity_id = n.nid');

      //  $nodes->condition('n.status', self::PUBLISHED);
       $nodes->orderBy('b.weight', 'asc');
      //  $nodes->skip($offset*$limit);
      //  $nodes->take($limit);
      //  $nodes->fields('b', ['nid']);
       $nodes->fields('eu', ['field_external_url_uri']);
       $nodes->fields('n', ['nid','type','title']);
       $results = $nodes->execute()->fetchAll();
    //  dd($results);

        //   ->get(['b.nid', 'n.type']);
        $items = [];
      foreach($results as $key => $node) {
      

        $items[] = [
     
          'id'=>$node->nid,
          'external_url'=>$node->field_external_url_uri,
          'title'=>$node->title,
          'type'=> $node->type,
 
        ];
      }
         return $items;
    


  //       // dd($node);
  //       //  if($node->type === RESOURCES_CONTENT_TYPE) {
  //           if($node->type) {
  //           //   $type = ['type' => DOCUMENT];
  //           //   $temp  = $type + $this->getResourceDocumentsByNid($node->nid, NULL);

  //             if(isset($temp['added_toolbox'])){
  //               unset($temp['added_toolbox']);
  //             }

  //             if(isset($temp['text'])){
  //               unset($temp['text']);
  //             }

  //             if(isset($temp['bookmarked'])){
  //               unset($temp['bookmarked']);
  //             }

  //             if(isset($temp['authors'])){
  //               unset($temp['authors']);
  //             }

  //             if(!empty($temp) && isset($temp['trophy'])){
  //              unset($temp['trophy']);
  //             }

  //             $response[] = $temp;
  //        }
  //       //  if($node->type === NEWS_AND_ARTICLE_CONTENT_TYPE) {
  //           if($node->type) {
  //       //    $type = ['type' => ARTICLE];
  //       //    $temp  = $type + $this->getArticleData($node->nid, NULL);

  //          if(isset($temp['bookmarked'])){
  //             unset($temp['bookmarked']);
  //          }
  //          $response[] = $temp;
  //        }
  //     }
  //     $count = $this->getHotTopicsCount($user);
  //     return  ['items' => $response, 'hot_topic_count' => $count];
  // }

  // /**
  // * Get Recent activities count
  // * @param Int $timestamp Timestamp
  // * @return Int $count
  // */
  // public function getHotTopicsCount($user)
  // {
  //   $timestamp = \Drupal::database()->select('scfp_users_tracking')
  //     ->condition('uid', $user->uid)


  //   //   ->value('hot_topic_access');
  //     ->fields('hot_topic_access');

  //   // return \Drupal::database()->select('scf_hot_topics_ordering')
  //   //      ->condition('created', '>=', $timestamp )
  //   //      ->count();
  }




  // public function getResourceDocumentsByNid($nid, $eng_id = 0, $user_id = 0, $is_child = false) {
    public function getResourceDocumentsByNid() {
    // dd('c,sdnc');
      // $data = null;
      // $cache = getCache($nid, SCFP_NODE_RESOURCE_CACHE);
      // if ($cache && $this->isPublished($nid)) {
      //     $node = unserialize($cache);
      // } else {
          $nodes = \Drupal::database()->select('node_field_data', 'n');
          
          // dd( $nodes);


          // $nodes->condition('n.nid', $nid);
          // dd($nodes);
          // ->where('n.nid', $nid)

          // $nodes->condition('n.status', self::PUBLISHED);
          // // ->where('n.status', self::PUBLISHED)
          $nodes->LeftJoin('field_data_body', 'b', 'n.nid = b.entity_id');
          // //dd($nodes);
          // $nodes->LeftJoin('field_data_field_resource_label', 'l', 'n.nid = l.entity_id');
          $nodes ->LeftJoin('field_data_field_know_id', 'know', 'know.entity_id = n.nid');
          $nodes->LeftJoin('field_data_field_pdf_viewer_file'  ,'preview', 'preview.entity_id = n.nid');
          $nodes->leftJoin('node_revision__field_is_know_preview_available' ,'knowpreview', 'knowpreview.entity_id = n.nid');
          $nodes->leftJoin('node__field_upload_downloadable' ,'ud', 'ud.entity_id = n.nid');
          $nodes->leftJoin('node_revision__field_video', 'v', 'v.entity_id = n.nid');
          $nodes->leftJoin('field_data_field_is_secondary_resource', 's', 's.entity_id = n.nid');
          $nodes->leftJoin('node_revision__field_top_tool', 't', 't.entity_id = n.nid');
          $nodes->leftJoin('field_data_field_is_q_a', 'qa', 'qa.entity_id = n.nid');
          $nodes->leftJoin('node__field_external_url' ,'eu', 'eu.entity_id = n.nid');
          $nodes->leftJoin('node__field_asset_type' , 'at', 'at.entity_id = n.nid');
          $nodes->leftJoin('node__field_do_not_display_preview', 'dp', 'dp.entity_id = n.nid');
          $nodes->leftJoin('node__field_disable_download', 'field_disable_download', 'field_disable_download.entity_id = n.nid');
          $nodes->leftJoin('node__field_is_webcasts_podcasts', 'iwp', 'iwp.entity_id = n.nid');
          $nodes->leftJoin('node__field_podcast_webcast_category', 'cwp', 'cwp.entity_id = n.nid');
          $nodes->leftJoin('node__field_host_name', 'hwp', 'hwp.entity_id = n.nid');
          $nodes->leftJoin('node__field_is_external_pw' ,'ewp', 'ewp.entity_id = n.nid');
          $nodes->leftJoin('node__field_hosted_link_pw', 'lwp', 'lwp.entity_id = n.nid');
          // $nodes->leftJoin('field_data_field_date_of_the_podcast_webcas as dwp', 'dwp.entity_id = n.nid');
          $nodes->leftJoin('node__field_audio_video_upload', 'awp', 'awp.entity_id =  n.nid');
          $nodes->leftJoin('node__field_resource_label_know', 'rlk', 'rlk.entity_id = n.nid');


           $nodes->fields('n', ['nid','title']);
         //  $nodes->fields('l', ['category','text']);
           $nodes->fields('b', ['body_summary']);
           $nodes->fields('know', ['field_know_id_value']);
           $nodes->fields('preview', ['field_pdf_viewer_file_fid']);
           $nodes->fields('knowpreview', ['field_is_know_preview_available_value']);
           $nodes->fields('ud', ['field_upload_downloadable_target_id']);
           $nodes->fields('v', ['field_video_target_id']);
           $nodes->fields('s', ['field_is_secondary_resource_value']);
           $nodes->fields('t', ['field_top_tool_value']);
           $nodes->fields('qa', ['field_is_q_a_value']);
           $nodes->fields('eu', ['field_external_url_uri']);
           $nodes->fields('at', ['field_asset_type_value']);
           $nodes->fields('dp', ['field_do_not_display_preview_value']);
           $nodes->fields('field_disable_download', ['field_disable_download_value']);
           $nodes->fields('iwp', ['field_is_webcasts_podcasts_value']);
           $nodes->fields('cwp', ['field_podcast_webcast_category_target_id']);
           $nodes->fields('hwp', ['field_host_name_value']);
           $nodes->fields('ewp', ['field_is_external_pw_value']);
           $nodes->fields('lwp', ['field_hosted_link_pw_uri']);
        //  $nodes->fields('dwp', ['field_date_of_the_podcast_webcas_value']);
          $nodes->fields('awp', ['field_audio_video_upload_target_id']);
          $nodes->fields('rlk', ['field_resource_label_know_value']);
          $results = $nodes->execute()->fetchAll();



//  dd($results);



 $data = [];
 foreach($results as $key => $node) {
 

   $data[] = [

     'id'=>$node->nid, 
    'title'=>$node->title,
    'summary'=>$node->body_summary,
    'know_id'=>$node->field_know_id_value,
    'preview_id'=>$node->field_pdf_viewer_file_fid,
    'hasknowpreview'=>$node->field_is_know_preview_available_value,
    'download_fid'=>$node->field_upload_downloadable_target_id,
    'video_fid'=>$node->field_video_target_id,
    'is_secondary'=>$node->field_is_secondary_resource_value,
    'trophy'=>$node->field_top_tool_value,
    'is_qa'=>$node->field_is_q_a_value,
    'ext_url'=>$node->field_external_url_uri,
    'asset_type'=>$node->field_asset_type_value,
    'do_not_display_preview'=>$node->field_do_not_display_preview_value,
    'disable_download'=>$node->field_disable_download_value,
    'is_webcasts_podcasts'=>$node->field_is_webcasts_podcasts_value,
    'podcast_webcast_category'=>$node->field_podcast_webcast_category_target_id,
    'podcast_webcast_host_name'=>$node->field_host_name_value,
    'is_external_pw'=>$node->field_is_external_pw_value,
    'hosted_link_pw'=>$node->field_hosted_link_pw_uri,
    'podcast_webcast_audio_video'=>$node->field_audio_video_upload_target_id,
    'resource_label_know'=>$node->field_resource_label_know_value,

    
   

   ];
 }



//  dd($data);
    return $data;
    //  dd($nodes);




          // ->select(array('n.nid as id',
          // 'n.title as title',
          // 'field_resource_label_tid as category','b.body_value as text',
          // 'b.body_summary as summary',
          // 'know.field_know_id_value as know_id',
          // 'preview.field_pdf_viewer_file_fid as preview_id',
          // 'knowpreview.field_is_know_preview_available_value as hasknowpreview',
          // 'ud.field_upload_downloadable_fid as download_fid',
          // 'v.field_video_fid as video_fid',
          // 's.field_is_secondary_resource_value as is_secondary',
          // 't.field_top_tool_value as trophy',
          // 'qa.field_is_q_a_value as is_qa',
          // 'eu.field_external_url_url as ext_url',
          // 'at.field_asset_type_value as asset_type',
          // 'dp.field_do_not_display_preview_value as do_not_display_preview',
          // 'field_disable_download.field_disable_download_value as disable_download',
          // 'iwp.field_is_webcasts_podcasts_value as is_webcasts_podcasts',
          // 'cwp.field_podcast_webcast_category_tid as podcast_webcast_category',
          // 'hwp.field_host_name_value as podcast_webcast_host_name',
          // 'ewp.field_is_external_pw_value as is_external_pw',
          // 'lwp.field_hosted_link_pw_url as hosted_link_pw',
          // 'dwp.field_date_of_the_podcast_webcas_value as podcast_webcast_date',
          // 'awp.field_audio_video_upload_fid as podcast_webcast_audio_video',
          // 'rlk.field_resource_label_know_value as resource_label_know',
          // ))
          // ->get();
  //         if (isset($results['0'])) {
  //             $node = $results['0'];
  //             // setForeverCache($nid, serialize($node), SCFP_NODE_RESOURCE_CACHE);
  //         }
  //     }

  //     if (!empty($node)) {
  //         $env = getVariable('know_api_environment');
  //         $download = getVariable($env . '_download_document');
  //         $preview = getVariable($env . '_preview_document');
  //         $charge_code = getVariable($env . '_chargecode');
  //         $asset_type = isset($node->asset_type) && !empty($node->asset_type) ? $node->asset_type : 'document';
  //         $doc_type = $this->getDocumentType($node->id, $is_child);

  //         //download_link
  //         $download_url = "";
  //         $file_details = [];
  //         if ($node->know_id > 0) {
  //             $download_url = str_replace(':know_id', $node->know_id, $download);
  //             $download_url = str_replace(':chargeCode', $charge_code, $download_url);
  //             $download_url = str_replace(':asset_type', $asset_type, $download_url);
  //             $keystring = getVariable('know_downlod_preview_new_old_api');
  //             if($keystring == 'new') {
  //               $download_url = str_replace(':FMNO', '&fmno='.$this->getUserCryptFmno($user_id), $download_url);
  //             } else {
  //               $download_url = str_replace(':FMNO', '&fmno='.$this->getUserFmno($user_id), $download_url);
  //             }
  //             $file_details = $this->GetKnowFileDetails($node->id);
  //           // Fetch size of KNOW document ID
  //         } else {
  //             if ($doc_type == 'video') {
  //                 $download_url = ($node->video_fid > 0) ? SITE_PATH . '/download/file/' . $node->video_fid : '';
  //                 $file_details = $this->GetFileDetails($node->video_fid);
  //             } else {
  //                 $download_url = ($node->download_fid > 0) ? SITE_PATH . '/download/file/' . $node->download_fid : '';
  //                 $file_details = $this->GetFileDetails($node->download_fid);
  //             }
  //         }

  //         // Disable downalod
  //         if (isset($node->disable_download) && $node->disable_download == 1) {
  //             $download_url = '';
  //         }

  //         //iframe_url
  //         $preview_url = "";
  //         if ($node->hasknowpreview == 1) {
  //             $preview_url = str_replace(':know_id', $node->know_id, $preview);
  //             $preview_url = str_replace(':chargeCode', $charge_code, $preview_url);
  //             $preview_url = str_replace(':asset_type', $asset_type, $preview_url);
  //             $keystring = getVariable('know_downlod_preview_new_old_api');
  //             if($keystring == 'new') {
  //               $preview_url = str_replace(':FMNO', '&fmno='.$this->getUserCryptFmno($user_id), $preview_url);
  //             } else {
  //               $preview_url = str_replace(':FMNO', '&fmno='.$this->getUserFmno($user_id), $preview_url);
  //             }
  //         } else {
  //             if ($doc_type == 'video') {
  //                 $temp = $this->getFilepathFromFid($node->video_fid);
  //                 $preview_url = urldecode($temp);
  //             } else {
  //                 $temp = $this->getFilepathFromFid($node->preview_id);
  //                 $preview_url = urldecode($temp);
  //             }
  //         }

  //         if (isset($node->do_not_display_preview) && $node->do_not_display_preview ==1) {
  //             $preview_url = "";
  //         }

  //         $data['id'] = $node->id;
  //         $data['title'] = !empty($node->title) ? $node->title : '';
  //         // Get document type
  //         $data['document_type'] = $doc_type;
  //         // Get document category
  //         $data['category'] = (isset($node->category) && $node->category > 0) ? $this->getTermName($node->category) : "";
  //         if ($node->know_id > 0 && isset($node->resource_label_know) && !empty($node->resource_label_know)) {
  //             $data['category'] = $node->resource_label_know;
  //         }
          
  //         if (isset($node->is_qa) && $node->is_qa == 1) {
  //             $data['document_type'] = 'qa';
  //             $data['category'] = 'Q&A';
  //         }
  //         $data['summary'] = $node->summary;
  //         $data['text'] = $node->text;
  //         $data['download_link'] = $download_url;
  //         $data['file_type'] = isset($file_details['type']) ? $file_details['type'] : '';
  //         $data['file_size'] = isset($file_details['filesize']) ? $file_details['filesize'] : '';
  //         $data['iframe_url'] = $preview_url;
  //         $data['bookmarked'] = $this->isBookmarkedNode($eng_id, $node->id, null, DOCUMENT);
  //         $data['is_secondary'] = isset($node->is_secondary) ? $node->is_secondary : 0;
  //         $data['is_qa'] = isset($node->is_qa) ? $node->is_qa : 0;
  //         $data['trophy'] = ($node->trophy === 1) ? true : false;
  //         $data['external_url'] = isset($node->ext_url) ? $node->ext_url : '';
  //         $data['asset_type'] = $asset_type;

  //         $data['is_podcast_webcast'] = 0;
  //         if (isset($node->is_webcasts_podcasts) &&  $node->is_webcasts_podcasts === 1) {
  //             $data['is_podcast_webcast'] = 1;
  //             $data['document_type']  = 'video';
  //             $data['category']  = isset($node->podcast_webcast_category) && $node->podcast_webcast_category > 0 ? $this->getTermName($node->podcast_webcast_category) : '';
  //             $data['date'] = date('F d, Y', strtotime($node->podcast_webcast_date));
  //             $data['text'] = isset($node->podcast_webcast_host_name) ? $node->podcast_webcast_host_name : '';
  //             if(isset($node->is_external_pw) && $node->is_external_pw === 1) {
  //                 $data['external_url'] = isset($node->hosted_link_pw) ? $node->hosted_link_pw : '';
  //             } else {
  //                 $data['external_url'] = isset($node->podcast_webcast_audio_video) ? $this->getFilepathFromFid($node->podcast_webcast_audio_video) : '';
  //             }
  //         }
  //     }
  //     return $data;
  }

}
?>