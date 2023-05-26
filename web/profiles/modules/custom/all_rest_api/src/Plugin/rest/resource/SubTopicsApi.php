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
 *   id = "sub_topics",
 *   label = @Translation("Sub Topics   API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-subtopics/63"
 *   }
 * )
 */


class SubTopicsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

      // $response['overview'] = $this->getOverview();
      $response['tabs'] = $this->getData();
      $response['Overview'] = $this->explanations();


      $info_text =  'The McKinsey Strategy Method';
      $meta = ['info_text'=>$info_text,'info_description' => $termname->description,'business_strategy'=>$business_strategy];

    

       $data = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];  
      $results = new ResourceResponse($data);
   
       //  return  jsonsuccess($results);
       return $results;


    //   if(empty($topic_id) || $topic_id == 0) {
    //     $meta = ['info_text'=>'','info_description' => '','business_strategy'=> false];
    //     return jsonSuccess([], $meta);
    //   }
  
    //   $info_text =  'The McKinsey Strategy Method';
    //   $business_strategy = false;
    //   $response = $repository->getData($topic_id);
    //   $termname = $repository->getDetailsfromTid($topic_id);
    //   if($termname->uuid === '0ae477df-c402-41dc-8c19-4b64df89b99f') {
    //     $business_strategy = true;
    //     $info_text = $repository->getFieldValue('field_help_description', $topic_id, 'taxonomy_term', $data = 'value');;
    //   }
  
    //   $meta = ['info_text'=>$info_text,'info_description' => $termname->description,'business_strategy'=>$business_strategy];
    //   return jsonSuccess($response, $meta);




  }





//   public function getData($topic_id) {
    public function getData() {
   //     $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,1 ,TRUE);
    

    $vid = '7_building_block';

    $terms = \Drupal::database()->select('taxonomy_term_field_data' ,'t');
      $terms->leftJoin('taxonomy_term__field_is_default' ,'d', 'd.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term_revision__field_icon_class' ,'i', 'i.entity_id = t.tid');
      $terms->condition('vid', $vid);
      $terms->orderBy('t.weight', 'asc');
      $terms->fields('t', ['tid','name']);
      $terms->fields('d', ['field_is_default_value']);
      $terms->fields('i', ['field_icon_class_value']);
      $results = $terms->execute()->fetchAll();

    $result = [];
    foreach ($results as $term) {
        $result[] = array(
         'id'=> $term->tid,
         'active'=> ($term->field_is_default_value == 1) ? "True" : "False",
         'icon_class'=> $term->field_icon_class_value,
         'title'=>$term->name

    
        ) ;
    }
    
    return $result;

      }




 public  function  explanations(){


        $vid = '7_building_block';

        $terms = \Drupal::database()->select('taxonomy_term_field_data' ,'t');
        $terms->leftJoin('taxonomy_term_revision__field_overview_question', 'h', 'h.entity_id = t.tid');
          $terms->leftJoin('taxonomy_term__field_is_default' ,'d', 'd.entity_id = t.tid');
          $terms->leftJoin('taxonomy_term_revision__field_icon_class' ,'i', 'i.entity_id = t.tid');
          $terms->condition('vid', $vid);
          $terms->orderBy('t.weight', 'asc');

          $terms->fields('t', ['tid','name','description__value']);
          $terms->fields('h', ['field_overview_question_value']);
          $terms->fields('d', ['field_is_default_value']);
          $terms->fields('i', ['field_icon_class_value']);

          $results = $terms->execute()->fetchAll();

        //  dd($results);
        $result = [];
        foreach ($results as $term) {
    
      
        $result[] = array(
  
          'headline'=> $term->field_overview_question_value,
          'text'=> $term->description__value,
          'icon_class'=> $term->field_icon_class_value,
          'title'=>$term->name
    
        ) ;
    }
       $res['tab_explanations']= $result;    

        return $res;


      //   foreach ($terms as $key=>$value) {
      //     $tabs[$key]['id'] = $value->id;
      //     $tabs[$key]['title'] = $value->title;
      //     $tabs[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '' ;
      //     $tabs[$key]['active'] = ($value->active == 1) ? true : false;
      //   }

      //   $overview_data = $this->getOverview($topic_id, $vid);

      // }
      // else {
      //   // For Other Except Business Strategy
      //   $introduction_string = 'Introduction of '. $this->getTermName($topic_id);
      //   $childs =  $this->getChildFromParentTid($topic_id);
      //   foreach ($childs as $key=>$value) {
      //     $tabs[$key]['id'] = $value->id;
      //     $tabs[$key]['title'] = $value->title;
      //     $tabs[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '' ;
      //     $tabs[$key]['active'] = ($value->active == 1) ? true : false;
      //   }
      //   $overview_data = $overview_data = $this->getOverview($topic_id);
      // }
      // $result = ['overview' => $overview_data,'tabs'=> $tabs];
      // $response = $result;
      // return $response;

  }

/**
 * getOverview
 * @param  Integer $topic_id
 * @param  Integer $vid (Only for bussiness strategy)
 * @return Array
 */
  // public function getOverview($topic_id, $vid = 0) {
    public function getOverview() {
     $overview_data['title'] = 'Overview';
     $overview_data['id'] = 0;
    
      $terms = \Drupal::database()->select('taxonomy_term_field_data' , 't');
      $terms->leftJoin('taxonomy_term_revision__field_icon_class', 'h', 'h.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term_revision__field_overview_question', 'q', 'q.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term_revision__field_exclude_display' , 'ex', 'ex.entity_id = t.tid');


      // $terms->where(function ($query) {
      //     $query->where('ex.field_exclude_display_value', '=', 0)
      //     ->orWhereNull('ex.field_exclude_display_value');
      // });
      $terms->orderBy('t.weight',   'asc');
      $terms->fields('t', ['tid','name','description__value']);
      $terms ->fields('h', ['field_icon_class_value']);
      $terms ->fields('q', ['field_overview_question_value']);
      $terms -> range(0,10);
      // dd($terms);
      $results =  $terms->execute()->fetchAll();
      // dd($results);


      foreach ($results as $key=>$value) {
        $tab_explanations[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '';
        $tab_explanations[$key]['title'] = $value->name;
        $tab_explanations[$key]['headline'] = $value->question;
        // $tab_explanations[$key]['headline'] = $this->stripTags($value->question);
        $tab_explanations[$key]['text'] = $value->text;
        // $tab_explanations[$key]['text'] = $this->stripTags($value->text);
      }









    $overview_data = [];
    // $overview_nid = $this->getOverviewNID($topic_id);
    // $additional_knowledge = $this->getMultiFieldValue('field_multidocument_resource', $overview_nid, 'node', $data = 'target_id');
    $overview_data['title'] = 'Overview';
    $overview_data['id'] = 0;
    // $overview_data['introduction']['headline'] = $this->stripTags($this->getNodeTitle($overview_nid));
    // $overview_data['introduction']['text'] = $this->getFieldValue('body', $overview_nid, 'node');
    $overview_data['additional_knowledge']['headline'] = 'Additional Knowledge';
    if($vid == 0){
      $overview_data['additional_knowledge']['headline'] = 'Core Materials';
    }


    //additional_knowledge
    $additional_docs = [];
    foreach($additional_knowledge as $nid) {
      $temp = $this->getResourceDocumentsByNid($nid);
      if(is_array($temp)) {
        if($temp['is_secondary'] == 0 && $temp['is_qa'] == 0) {
          $additional_docs[]= $this->getResourceDocumentsByNid($nid);
        }
      }
    }
    $overview_data['additional_knowledge']['documents'] = $additional_docs;
    // dd($overview_data);

  
    $videos = [];
    $vid_nids = $this->getMultiFieldValue('field_videos', $topic_id, 'taxonomy_term', $data = 'target_id');
    foreach($vid_nids as $vid_nid) {
      $temp = $this->getVideoDetailsByNid($vid_nid);
      if(isset($temp)) {
        $videos[]= $temp;
      }
    }
    $overview_data['videos'] = $videos;

    //$tab_explanations
    $tab_explanations = [];
    if($vid > 0) {




      $terms = \Drupal::database()->select('taxonomy_term_field_data' , 't');
      $terms->leftJoin('taxonomy_term_revision__field_icon_class', 'h', 'h.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term_revision__field_overview_question', 'q', 'q.entity_id = t.tid');
      $terms->leftJoin('taxonomy_term_revision__field_exclude_display' , 'ex', 'ex.entity_id = t.tid');


      // $terms->where(function ($query) {
      //     $query->where('ex.field_exclude_display_value', '=', 0)
      //     ->orWhereNull('ex.field_exclude_display_value');
      // })
      $terms->orderBy('t.weight', 'asc');
      $terms->fields('t', ['tid','name','description']);
      $terms ->fields('h', ['field_icon_class_value']);
      $terms ->fields('q', ['field_overview_question_value']);
      $results =  $terms->execute()->fetchAll();
      // dd($results);


        // ->where('vid', $vid)
        // ->select([
        //   't.name as title',
        //   't.tid as id',
        //   't.description as text',
        //   'h.field_icon_class_value as icon_class',
        //   'q.field_overview_question_value as question'
        // ])
        // ->orderBy('t.weight', 'asc')
        // ->get();



      foreach ($results as $key=>$value) {
        $tab_explanations[$key]['icon_class'] = isset($value->icon_class) ? $value->icon_class : '';
        $tab_explanations[$key]['title'] = $value->title;
        // $tab_explanations[$key]['headline'] = $this->stripTags($value->question);
        // $tab_explanations[$key]['text'] = $this->stripTags($value->text);

        // dd($tab_explanations);
      }




      
    } else {
      $terms = \Drupal::database()->select('taxonomy_term_data', 't')
        ->join('taxonomy_term_hierarchy', 'h', 't.tid = h.tid')
        ->where('h.parent', $topic_id)
        ->leftJoin('field_data_field_is_default', 'd', 'd.entity_id = t.tid')
        ->leftJoin('taxonomy_term_revision__field_exclude_display' , 'ex', 'ex.entity_id = t.tid')
        ->where(function ($query) {
          $query->where('ex.field_exclude_display_value', '=', 0)
          ->orWhereNull('ex.field_exclude_display_value');
      })
        ->select([
          't.name as title',
          't.tid as id',
          't.description as text'
        ])
        ->orderBy('t.weight', 'asc')
        ->get();
        foreach ($terms as $key=>$value) {
          $tab_explanations[$key]['icon_class'] = '';
          $tab_explanations[$key]['title'] = $value->title;
          $tab_explanations[$key]['headline'] = '';
          $tab_explanations[$key]['text'] = $this->stripTags($value->text);
        }
    }
    $overview_data['tab_explanations'] = $tab_explanations;
    return $overview_data;
  }


  







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
      return  \Drupal::database()->select('field_data_'.$field_name)
          ->where('entity_id', $entity_id)
          ->where('entity_type', $entity_type)
          ->orderBy('delta', 'asc')

          // ->field([$field_name.'_'.$data .' as value']);
          ->get([$field_name.'_'.$data .' as value']);
  }



  public function getVideoDetailsByNid($nid)
  {
      $data = null;
      $nodes =  \Drupal::database()->select('node as n')
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





  }

 

?>