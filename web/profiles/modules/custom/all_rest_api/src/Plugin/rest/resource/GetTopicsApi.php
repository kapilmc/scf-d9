<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;
use Drupal\Core\Database\Database;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\serialization\Normalizer\NormalizerBase;
use Drupal\Component\Serialization;
use Symfony\Component\Serializer\Encoder;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;





/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "Topics API",
 *   label = @Translation("Topics API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-topics"
 *   }
 * )
 */

class GetTopicsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

  // $response = $this->Topics();
  // $response = $this->getTerms();
  $response = $this->getTopicsData();
  // $response = $this->getTermUUID();




    $meta = ['info_text'=>'Topics vocabulary'];

   $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  
    $results = new ResourceResponse($data);
    return $results;
     }




// -----------------d9 method use  get topics data --------------



public function Topics(){
 
    $vid = 'topics';
    // $parent_tid = 4129; 
    // $depth = 1; 
    // $load_entities = FALSE; 
    // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent_tid, $depth, $load_entities,TRUE);
  //  dd($terms);


  // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  // dd($terms);
 

  $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,5 ,TRUE);
    // dd($terms[5]->parents->value);  
  //  4129

 $result = [];
//   $children = '';
foreach ($terms as  $term) {

  if($term->name->value=='Corporate Finance'){
    $Finance[] = array(
     'id'=> $term->tid->value,
     'title'=> $term->name->value,
     'tooltip'=>$term->field_tool_tip->value,
     'external'=>$term->field_external_url->value,
     'active' => isset($term->active) && $term->active == 1 ? true : false,
     'parents_id'=>$term->parents->value,
    ) ;
  }
  else if($term->name->value=='Stakeholders'){
    $Stakeholder[] = array(
     'id'=> $term->tid->value,
     'title'=> $term->name->value,
     'tooltip'=>$term->field_tool_tip->value,   
     'external'=>$term->field_external_url->value,
     'active' => isset($term->active) && $term->active == 1 ? true : false,
     'parents_id'=>$term->parents->value,
    ) ;
  }
  else if($term->name->value=='Strategy'){
    $Strategy[] = array(
     'id'=> $term->tid->value,
     'title'=> $term->name->value,
     'tooltip'=>$term->field_tool_tip->value,
     'external'=>$term->field_external_url->value,
     'active' => isset($term->active) && $term->active == 1 ? true : false,
     'parents_id'=>$term->parents,
    ) ;
  }

$leads=['Corporate Finance'=>$Finance,'Stakeholders'=>$Stakeholder,'Strategy'=>$Strategy];
$wins=['Corporate Finance'=>$Finance,'Stakeholders'=>$Stakeholder,'Strategy'=>$Strategy];
$inspirs=['Corporate Finance'=>$Finance,'Stakeholders'=>$Stakeholder,'Strategy'=>$Strategy];

$lead=['lead'=>$leads,'win'=>$wins,'inspire'=>$inspirs];

 
}
return $lead;

  
  }




// // -------------d7 method use---------------------------------------------------------------------------------
  public function getTopicsData() {
    // dd('knnasndcas');
      // $cache = $this->getCache('topics', SCFP_TAXONOMY_VOCAB_TOPIC_CACHE);
      // if ($cache) {
       
      //     return $cache;
      // } else {
          $all_topics[] = ['id' => 0, 'title' => 'All Topics', 'active' => true, 'child' => []];
          // dd($all_topics);

          $topics_vid = 'topics';
          // dd($topics_vid);
          
        //   $topics_vid = $this->getVidfromVocabMachineName('topics');

          $parent_topics = $this->getTerms(0, $topics_vid);
          // dd($parent_topics);
          $lead = $win = [];
          foreach ($parent_topics as $parent_topic) {
            // dd($parent_topic);
              $lead[$parent_topic['title']] = $this->getTerms($parent_topic['id'], $topics_vid);
              $win[$parent_topic['title']] = $this->getTerms($parent_topic['id'], $topics_vid, 1);
          }

       
          $data = ['lead' => $lead, 'win' => $win, 'inspire' => $lead];

          // setForeverCache('topics', $data, SCFP_TAXONOMY_VOCAB_TOPIC_CACHE);

        // dd($data);
          return $data;
      }
   
  // }
  //First, second level terms
  public function getTerms($parent, $vid, $all = 0, $count = 0)  {
   
      $data = [];
      $count++;
      // $parent ='4129';
      if ($parent > 0 && $all === 1) {
          // $st_uuid = $this->getTermUUID($parent);
          if ($st_uuid == 'df2178fb-c698-4cf5-a8fb-25695acb382c') {

              $data[] = ['id' => 0, 'title' => 'All Topics', 'active' => true, 'child' => []];
          }
      }

      $terms =  \Drupal::database()->select('taxonomy_term_field_data',  't');
      $terms->Join(' taxonomy_term__parent', 'h', 'h.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term__field_is_default', 'd', 'd.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term__field_tool_tip', 'tip', 'tip.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term__field_exclude_display', 'ex', 'ex.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term__field_external_url', 'external', 'external.entity_id = t.tid');
      // $terms->leftJoin('taxonomy_term__field_video_thumbnail', 'img', 'img.entity_id = t.tid');

      
    //   247 '4129'
      $terms->condition('parent_target_id', $parent);
      // $terms->condition('parent_target_id', '4129');
      $terms ->condition('vid', $vid);

      // $terms->condition(function ($query) {
      //         $query->condition('ex.field_exclude_display_value', '=', 0)
      //             ->orWhereNull('ex.field_exclude_display_value')
      //     });
        $terms->orderBy('t.weight', 'ASC');
        $terms->orderBy('t.tid', 'ASC');



        $terms->fields('t', [ 'tid','name']);
        $terms ->fields('h', [ 'parent_target_id']);
        $terms->fields('d', [ 'field_is_default_value']);
        $terms->fields('tip', [ 'field_tool_tip_value']);
        $terms->fields('external', [ 'field_external_url_uri']);
        // $terms->fields('img', [ 'field_video_thumbnail_target_id']);
        //   ->execute();
        //   dd($terms);
         $result = $terms->execute()->fetchAll();

    //  dd($result);


        //   ->select([
        //       't.tid as id',
        //       't.name as title',
        //       'h.parent as parent',
        //       'd.field_is_default_value as active',
        //       'tip.field_tool_tip_value as tip',
        //       'external.field_external_url_url as external_url'
        //   ])
        //   ->get();



        $data = [];
        foreach ($result as $term) {

         

        //   $fid = $term->field_video_thumbnail_target_id;
        //   // dd($fid);
        //     $file = \Drupal\file\Entity\File::load('408'); 
        //   //  dd($file);
        //   // $file_relative_url = $file->createFileUrl();
 
        //   // dd($file_relative_url);

        //  $path = $file->createFileUrl(FALSE);
        // //  dd($path);


       
              //   //get data
              //   $data[] = [


              //     'id' => $term->tid,
              //     'title' => $term->name,
              //     'tooltip' => isset($term->field_tool_tip_value) && !empty($term->field_tool_tip_value) ? $term->field_tool_tip_value : "",
              //     'active' => isset($term->active) && $term->active == 1 ? true : false,
              //     'external' => isset($term->field_external_url_uri) && !empty($term->field_external_url_uri) ? $term->field_external_url_uri : "",
              //     'image' => isset($term->field_video_thumbnail_target_id) && !empty($term->field_video_thumbnail_target_id) ? $term->field_video_thumbnail_target_id : "",
              //     // 'image'=>$path,
   
              //   ];
              // }
          

             if ($parent > 0) {
               if ($count == 2) {
                  $data[] = [
                 
                      'id' => $term->tid,
                      'title' => $term->name,
                      'tooltip' => isset($term->field_tool_tip_value) && !empty($term->field_tool_tip_value) ? $term->field_tool_tip_value : "",
                      'active' => isset($term->active) && $term->active == 1 ? true : false,
                      'external' => isset($term->field_external_url_uri) && !empty($term->field_external_url_uri) ? $term->field_external_url_uri : "",
                      // 'image' => isset($term->field_video_thumbnail_target_id) && !empty($term->field_video_thumbnail_target_id) ? $term->field_video_thumbnail_target_id : "",
                      // 'image'=> $path,
                  ];
                  
              } else {
                  $data[] = [
                   
                      'id' => $term->tid,
                      'title' => $term->name,
                      'tooltip' => isset($term->field_tool_tip_value) && !empty($term->field_tool_tip_value) ? $term->field_tool_tip_value : "",
                      'active' => isset($term->active) && $term->active == 1 ? true : false,
                      'child' => $this->getTerms($term->tid, $vid, 0, $count),
                      'external' => isset($term->field_external_url_uri) && !empty($term->field_external_url_uri) ? $term->field_external_url_uri : "",
                      // 'image' => isset($term->field_video_thumbnail_target_id) && !empty($term->field_video_thumbnail_target_id) ? $term->field_video_thumbnail_target_id : "",
                      // 'image'=> $path,
                  ];
              }
          } else {
              $data[] = [
             
                  'id' => $term->tid,
                  'title' => $term->name,
                  'tooltip' => isset($term->field_tool_tip_value) && !empty($term->field_tool_tip_value) ? $term->field_tool_tip_value : "",
                  'active' => isset($term->active) && $term->active == 1 ? true : false,
                  'external' => isset($term->field_external_url_uri) && !empty($term->field_external_url_uri) ? $term->field_external_url_uri : "",
                  // 'image' => isset($term->field_video_thumbnail_target_id) && !empty($term->field_video_thumbnail_target_id) ? $term->field_video_thumbnail_target_id : "",
                  // 'image'=> $path,
              ];
          }
      
        }
        // dd($data);

          return $data;
      // }
   
  }

// }








  // // public function getTermUUID($tid){
  // //   dd('l;dskx;d');
  //   public function getTermUUID(){
  //     // dd('kd;sakd;xaskd');sssss

      
  //     // $cache = \Drupal::cache();
  //     // dd($cache);
  //     // $rwes= $tid;
  //     // dd($res);

  //   // dd('cmnbvsncvb');
  //     // $cache = getCache($tid, SCFP_TERMS_UUID_FROM_TID);
  //     // if ($cache) {
  //     //     return unserialize($cache);
  //     // } else {
  //         $term['uuid'] = \Drupal::database()->select('taxonomy_term_data','t')
  //         // dd($term);
  //         ->condition('tid', $tid)
  //         // ->field['uuid'];


  //       ->fields('t', [ 'uuid']);
  //         // // ->value('uuid');
  //         // dd($term);

  //         if (isset($term['uuid']) && !empty($term['uuid'])) {
  //             // setForeverCache($tid, serialize($term['uuid']), SCFP_TERMS_UUID_FROM_TID);
  //         }
  //         // dd($term);
  //         dd($term);
  //         return $term['uuid'];
  //     }
  }





// }

// ?>