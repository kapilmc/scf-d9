<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\export_resource_report_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

class export_resource_report_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'export_resource_report_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $topics = getTopics(0, 'resource_category');
    // $resource_category = array_column($topics, 'name', 'tid');
    // $form_state['resource_category'] = $resource_category;
    // $form_state['topic_headers'] = topicHeaders();

    
  
    $form['resource_category'] = [
      '#type' => 'select',
      '#title' => t('Selected a resource category'),
    //   '#options' => $resource_category,
      '#required' => true,
    ];
  
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Export')
    ];
    return $form;







    // function downloadResourceByCategoryWise($resource_category, $file_name, $topic_headers) {
    //     try{
    //       $rows = $topic_headers;
    //       $query = \Drupal::database()->select('node', 'n');
         
    //       $query->join('resource_content_order', 'cat', 'cat.nid = n.nid');
    //       $query->join('field_data_field_topics_sub_topics', 'topic', 'topic.entity_id = n.nid');
    //       $query->join('taxonomy_term_data', 'td', 'td.tid = cat.resource_cat');
    //       $query->join('taxonomy_term_data', 'topic_td', 'topic_td.tid = topic.field_topics_sub_topics_tid');
          
    //       $query->condition('topic.entity_type', 'node')
    //             ->condition('td.tid', $resource_category)
    //             ->fields('topic_td', ['tid', 'name'])
    //             ->fields('n', ['nid', 'title'])
    //             ->orderBy('n.nid');
    //             // dd($query);
    //       $results = $query->execute();
    
    //     //   dd($results);
    //       $length = count($rows['subtopics']);
    //       $sub_topics = array_keys($rows['subtopics']);
    //       while($result = $results->fetchAssoc()) {
    //         if(!isset($rows[$result['nid']]))
    //           $rows[$result['nid']] = array_pad([$result['title']], $length, 'N');
    //           $key = array_search($result['tid'], $sub_topics);
      
    //         if($key !==false) {
    //           $rows[$result['nid']][$key] = 'Y';
    //         }
    //       }
      
    //       scf_reports_array_to_csv_download($rows, $file_name, ",");
    //     }catch(\Exception $e) {
    //       echo $e->getMessage();
    //     }
    //   }
      
    //   function topicHeaders() {
    //     $topics = getTopics();
    //     $topics_header = ['Topics'];
    //     $sub_topics_header = ['Sub topics'];
    //     foreach(array_keys($topics) as $key) {
    //       foreach ($topics[$key]['children'] as $topic_id => $value) {
    //         $children = count($value['children']);
    //         $topics_header[] = $value['name'];
    //         $padding = $children ? (count($topics_header)+$children-1) : count($topics_header);
    //         $topics_header = array_pad($topics_header, $padding, '');
      
    //         if($children) {
    //           foreach($value['children'] as $sub_topic_id => $sub_topic)
    //             $sub_topics_header[$sub_topic_id] = $sub_topic['name'];
    //         }else {
    //           $sub_topics_header[$topic_id] = $value['name'];
    //         }
    //       }
    //     }
    //     return [ 'topics' => $topics_header, 'subtopics' => $sub_topics_header];
    //   }
      
    //   function getTopics($parent = 0, $taxonomy = 'topics') {
    //     $tree = [];
    //     $query = \Drupal::database()->select('taxonomy_term_data', 'td')

    
    //              ->fields('td', ['tid', 'name'])
    //              ->fields('th', ['parent']);
                 
    //     $query->join('taxonomy_vocabulary', 'tv', 'tv.vid = td.vid');
    //     $query->leftJoin('taxonomy_term_hierarchy', 'th', 'th.tid = td.tid');
    //     $query->condition('tv.machine_name', $taxonomy)
    //           ->condition('th.parent', $parent);
    //     $query->orderBy('td.weight', 'ASC');
    //     // dd($query);
    //     // $results = $query->execute();
    //     $results = $query->execute()->fetchAll();
    //   dd($results);
    //     while ($result = $results->fetchAssoc()) {
    //       $tree[$result['tid']] = $result;
    //       $children = getTopics($result['tid'], $taxonomy);
    //       $tree[$result['tid']]['children'] = $children;
    //     }
      
    //     // return $tree;
         
    //   }









    //   return $form;






  }




  
  



















  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {

    $resource_category = $form_state['values']['resource_category'];
  $cat_name = $form_state['resource_category'][$resource_category];
  $file_name = 'resource_export_'.$cat_name.'.csv';
  downloadResourceByCategoryWise($resource_category, $file_name, $form_state['topic_headers']);
}

}


