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

/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "subtopics_resource",
 *   label = @Translation("Subtopics API"),
 *   uri_paths = {
 *     "canonical" = "/v1/api/get-subtopics/63"
 *   }
 * )
 */

class SubtopicsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    //  $response = ['message' => 'Hello, Sub topics api'];


    // return new ResourceResponse($response);


  //   public function getData($topic_id) {
  //     $tabs = $tab_explanations = [];
  //     $overview_data = [];
  //     $topic_uuid = $this->getTermUUID($topic_id);
  //       if($topic_uuid === '0ae477df-c402-41dc-8c19-4b64df89b99f') {
  //         //Load terms of 7 Building Block Vocab
  //         $vocab_name =  '7_building_block';
  //         $vid = $this->getVidfromVocabName($vocab_name);
          // $terms = DB::table('taxonomy_term_data as t')
          $terms = \Drupal::database()->select('taxonomy_term_data as t');
          // dd($terms);
          $terms->leftJoin('field_data_field_icon_class as h', 'h.entity_id', '=', 't.tid');
          $terms->leftJoin('field_data_field_is_default as d', 'd.entity_id', '=', 't.tid');
          // dd($terms);
           $terms ->where('vid', $vid);
          
            
          //  $terms ->select([
          //     't.name as title',
          //     't.tid as id',
          //     'h.field_icon_class_value as icon_class',
          //     'd.field_is_default_value as active',
          //  ]);
           
            $terms->orderBy('t.weight', 'asc');
            dd($terms);
  //           ->get();
  //         foreach ($terms as $key=>$value) {
  //           $tabs[$key]['id'] = $value->id;
  //           $tabs[$key]['title'] = $value->title;
  //           $tabs[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '' ;
  //           $tabs[$key]['active'] = ($value->active == 1) ? true : false;
  //         }
  
  //         $overview_data = $this->getOverview($topic_id, $vid);
  
  //       }
  //       else {
  //         // For Other Except Business Strategy
  //         $introduction_string = 'Introduction of '. $this->getTermName($topic_id);
  //         $childs =  $this->getChildFromParentTid($topic_id);
  //         foreach ($childs as $key=>$value) {
  //           $tabs[$key]['id'] = $value->id;
  //           $tabs[$key]['title'] = $value->title;
  //           $tabs[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '' ;
  //           $tabs[$key]['active'] = ($value->active == 1) ? true : false;
  //         }
  //         $overview_data = $overview_data = $this->getOverview($topic_id);
  //       }
  //       $result = ['overview' => $overview_data,'tabs'=> $tabs];
  //       $response = $result;
  //       return $response;
  
  //   }
  
  //   public function getVidfromVocabName($vocab_name) {
  //     return $this->getVidfromVocabMachineName($vocab_name);
  //     // $result = DB::table('taxonomy_vocabulary as t')
  //     //   ->where('machine_name', $vocab_name)
  //     //   ->select([
  //     //     't.vid as vid',
  //     //   ])
  //     //   ->get();
  //     // return (reset($result)->vid);
  //   }
  
  
  // /**
  //  * getOverview
  //  * @param  Integer $topic_id
  //  * @param  Integer $vid (Only for bussiness strategy)
  //  * @return Array
  //  */
  //   public function getOverview($topic_id, $vid = 0) {
  //     $overview_data = [];
  //     $overview_nid = $this->getOverviewNID($topic_id);
  //     $additional_knowledge = $this->getMultiFieldValue('field_multidocument_resource', $overview_nid, 'node', $data = 'target_id');
  //     $overview_data['title'] = 'Overview';
  //     $overview_data['id'] = 0;
  //     $overview_data['introduction']['headline'] = $this->stripTags($this->getNodeTitle($overview_nid));
  //     $overview_data['introduction']['text'] = $this->getFieldValue('body', $overview_nid, 'node');
  //     $overview_data['additional_knowledge']['headline'] = 'Additional Knowledge';
  //     if($vid == 0){
  //       $overview_data['additional_knowledge']['headline'] = 'Core Materials';
  //     }
  
  
  //     //additional_knowledge
  //     $additional_docs = [];
  //     foreach($additional_knowledge as $nid) {
  //       $temp = $this->getResourceDocumentsByNid($nid);
  //       if(is_array($temp)) {
  //         if($temp['is_secondary'] == 0 && $temp['is_qa'] == 0) {
  //           $additional_docs[]= $this->getResourceDocumentsByNid($nid);
  //         }
  //       }
  //     }
  //     $overview_data['additional_knowledge']['documents'] = $additional_docs;
  
  //     //videos
  //     $videos = [];
  //     $vid_nids = $this->getMultiFieldValue('field_videos', $topic_id, 'taxonomy_term', $data = 'target_id');
  //     foreach($vid_nids as $vid_nid) {
  //       $temp = $this->getVideoDetailsByNid($vid_nid);
  //       if(isset($temp)) {
  //         $videos[]= $temp;
  //       }
  //     }
  //     $overview_data['videos'] = $videos;
  
  //     //$tab_explanations
  //     $tab_explanations = [];
  //     if($vid > 0) {
  //       $terms = DB::table('taxonomy_term_data as t')
  //         ->leftJoin('field_data_field_icon_class as h', 'h.entity_id', '=', 't.tid')
  //         ->leftJoin('field_data_field_overview_question as q', 'q.entity_id', '=', 't.tid')
  //         ->leftJoin('field_data_field_exclude_display as ex', 'ex.entity_id', '=', 't.tid')
  //         ->where(function ($query) {
  //           $query->where('ex.field_exclude_display_value', '=', 0)
  //           ->orWhereNull('ex.field_exclude_display_value');
  //       })
  //         ->where('vid', $vid)
  //         ->select([
  //           't.name as title',
  //           't.tid as id',
  //           't.description as text',
  //           'h.field_icon_class_value as icon_class',
  //           'q.field_overview_question_value as question'
  //         ])
  //         ->orderBy('t.weight', 'asc')
  //         ->get();
  //       foreach ($terms as $key=>$value) {
  //         $tab_explanations[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '';
  //         $tab_explanations[$key]['title'] = $value->title;
  //         $tab_explanations[$key]['headline'] = $this->stripTags($value->question);
  //         $tab_explanations[$key]['text'] = $this->stripTags($value->text);
  //       }
  //     } else {
  //       $terms = DB::table('taxonomy_term_data as t')
  //         ->join('taxonomy_term_hierarchy as h', 't.tid', '=', 'h.tid')
  //         ->where('h.parent', $topic_id)
  //         ->leftJoin('field_data_field_is_default as d', 'd.entity_id', '=', 't.tid')
  //         ->leftJoin('field_data_field_exclude_display as ex', 'ex.entity_id', '=', 't.tid')
  //         ->where(function ($query) {
  //           $query->where('ex.field_exclude_display_value', '=', 0)
  //           ->orWhereNull('ex.field_exclude_display_value');
  //       })
  //         ->select([
  //           't.name as title',
  //           't.tid as id',
  //           't.description as text'
  //         ])
  //         ->orderBy('t.weight', 'asc')
  //         ->get();
  //         foreach ($terms as $key=>$value) {
  //           $tab_explanations[$key]['icon_class'] = '';
  //           $tab_explanations[$key]['title'] = $value->title;
  //           $tab_explanations[$key]['headline'] = '';
  //           $tab_explanations[$key]['text'] = $this->stripTags($value->text);
  //         }
  //     }
  //     $overview_data['tab_explanations'] = $tab_explanations;
  //     return $overview_data;


























    }



  
  }




?>