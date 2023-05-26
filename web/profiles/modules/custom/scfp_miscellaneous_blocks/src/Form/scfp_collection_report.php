<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\Form\scfp_collection_report.
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;
 

class scfp_collection_report extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_collection_report';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Download csv',
        '#submit' => array('::downloadCsv'),
];


// global $base_url;
// $insp_tid = scf_get_tid_from_uuid('622fc98c-2534-4549-a944-0ab24d6cbe2c');
// $lead_tid = scf_get_tid_from_uuid('47894b4f-1df2-43d0-9eec-33ba7232a72b');
// $win_tid = scf_get_tid_from_uuid('cf09255b-8e33-4287-8353-841cc5fe07c7');
// $journeys = [$insp_tid,$lead_tid,$win_tid];

$query = \Drupal::database()->select('node_field_data', 'n');
$query->leftjoin('field_data_body', 'bd', 'bd.entity_id = n.nid');

$query->leftjoin('field_data_field_is_secondary_resource', 's', 's.entity_id = n.nid');
$query->leftjoin('field_data_field_is_q_a', 'qa', 'qa.entity_id = n.nid');

$query->leftjoin('field_data_field_user_journey', 'j', 'j.entity_id = n.nid');
$query->leftjoin('field_data_field_topics_sub_topics', 't', 't.entity_id = n.nid');
$query->leftjoin('field_data_field_win_category', 'w', 'w.entity_id = n.nid');
$query->leftjoin('field_data_field_resource_category', 'c', 'c.entity_id = n.nid');
$query->leftjoin('field_data_field_multidocument_resource', 'm', 'm.entity_id = n.nid');
$query->addField('bd', 'body_value', 'body_value');
$query->addField('bd', 'body_summary', 'body_summary');
$query->addField('w', 'field_win_category_tid', 'field_win_category_tid');
$query->addField('c', 'field_resource_category_tid', 'field_resource_category_tid');
$query->addField('t', 'field_topics_sub_topics_tid', 'field_topics_sub_topics_tid');
$query->addField('n', 'title', 'title');
$query->addField('n', 'nid', 'nid');
$query->addField('n', 'status', 'status');
$query->addField('j', 'field_user_journey_tid', 'field_user_journey_tid');
$query->addField('qa', 'field_is_q_a_value', 'field_is_q_a_value');
$query->addField('m', 'field_multidocument_resource_target_id', 'field_multidocument_resource_target_id');
$query->condition('n.status', 1);
$query->condition('j.field_user_journey_tid', $journeys, "IN");
$query->condition('n.type', 'resource');
$query->condition('s.field_is_secondary_resource_value', 0);
$query->condition('m.field_multidocument_resource_target_id', 0, '>');
$query->distinct();
$query->orderBy('n.created', 'ASC');
// dd($query);
$pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10);
 $results = $pager->execute()->fetchAll();

$result = $query->execute()->fetchAll();


// dd($result);



$rows = [];
foreach ($result as $key => $item) {
    // dd($item);
  if((empty($item->field_is_q_a_value) || $item->field_is_q_a_value == 0) && $item->field_user_journey_tid > 0 ) {
    $journey_tid = !empty($item->field_user_journey_tid) ? $item->field_user_journey_tid : 0;

    $body = strip_tags($item->body_value);
    $summary = strip_tags($item->body_summary);

    $insp = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
    $win = isset($item->field_win_category_tid) && $item->field_win_category_tid > 0 ? scf_get_term_name_from_tid($item->field_win_category_tid): '';
    $lead = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
    $rcat = isset($item->field_resource_category_tid) && $item->field_resource_category_tid > 0 ? scf_get_term_name_from_tid($item->field_resource_category_tid): '';
    $coll = $item->title;
    $desc = !empty($summary) ? $summary : $body;
    $purl = $base_url.'#/document/popup/'.$item->nid;

    //win
    if($journey_tid == $win_tid) {
      $key = $journey_tid.'_'.$item->field_topics_sub_topics_tid.'_'.$item->field_win_category_tid;

      $lead = '';
      $insp = '';
      $win = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
      $rcat = isset($item->field_win_category_tid) && $item->field_win_category_tid > 0 ? scf_get_term_name_from_tid($item->field_win_category_tid): '';
    }

    //lead
    if($journey_tid == $lead_tid) {
      $key = $journey_tid.'_'.$item->field_topics_sub_topics_tid.'_'.$item->field_resource_category_tid;
      $lead = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
      $insp = '';
      $win = '';
      $rcat = isset($item->field_resource_category_tid) && $item->field_resource_category_tid > 0 ? scf_get_term_name_from_tid($item->field_resource_category_tid): '';
    }

    //insp
    if($journey_tid == $insp_tid) {
      $key = $journey_tid.'_'.$item->field_topics_sub_topics_tid.'_'.$item->field_resource_category_tid;
      $lead = '';
      $insp = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
      $win = '';
      $rcat = isset($item->field_resource_category_tid) && $item->field_resource_category_tid > 0 ? scf_get_term_name_from_tid($item->field_resource_category_tid): '';
    }

    $refnid = !empty($item->field_multidocument_resource_target_id) ? (int)$item->field_multidocument_resource_target_id : 0;
    $ref_data = [];
    $ref_data = get_ref_node_data($refnid);
    $rtitle = isset($ref_data['title']) ? $ref_data['title'] : '';
    $know = isset($ref_data['know_id']) ? $ref_data['know_id'] : '';
    $pdf = isset($ref_data['pdf']) ? $ref_data['pdf'] : '';
    $url = $base_url.'#/document/popup/'.$refnid;
    if(!empty($rtitle)) {
      $key = $key.'_'.$refnid;
      $rows[$key] = [
        strip_tags($insp),
        $win,
        $lead,
        $rcat,
        $coll,
        text_summary($desc, $format = NULL, $size = 200),
        $purl,
        $rtitle,
        $know,
        $pdf,
        $url,
      ];
    }
  }
}




















$header =['Inspire your CEO/CFO dialogue',
'Compete to Win',
'Lead your engagement',
'Resource Category',
'Collections',
'Description',
'URL',
'Resource Title',
'Know ID',
'PDF',
'URL'];
//   }
  




$form['table'] = [
'#type' => 'table',
'#header' => $header,
'#rows' => $rows,
'#empty' => t('There are no data.'),
];

$form['pager'] = [
 '#type' => 'pager'
];

 return $form;
  }




function downloadCsv() {

        $insp_tid = scf_get_tid_from_uuid('622fc98c-2534-4549-a944-0ab24d6cbe2c');
        $lead_tid = scf_get_tid_from_uuid('47894b4f-1df2-43d0-9eec-33ba7232a72b');
        $win_tid = scf_get_tid_from_uuid('cf09255b-8e33-4287-8353-841cc5fe07c7');
        $journeys = [$insp_tid,$lead_tid,$win_tid];
        $data = [];
        global $base_url;
        $query = \Drupal::database()->select('node_field_data', 'n');
        $query->leftjoin('field_data_body', 'bd', 'bd.entity_id = n.nid');
      
        $query->leftjoin('field_data_field_is_secondary_resource', 's', 's.entity_id = n.nid');
        $query->leftjoin('field_data_field_is_q_a', 'qa', 'qa.entity_id = n.nid');
      
        $query->leftjoin('field_data_field_user_journey', 'j', 'j.entity_id = n.nid');
        $query->leftjoin('field_data_field_topics_sub_topics', 't', 't.entity_id = n.nid');
        $query->leftjoin('field_data_field_win_category', 'w', 'w.entity_id = n.nid');
        $query->leftjoin('field_data_field_resource_category', 'c', 'c.entity_id = n.nid');
        $query->leftjoin('field_data_field_multidocument_resource', 'm', 'm.entity_id = n.nid');
        $query->addField('bd', 'body_value', 'body_value');
        $query->addField('bd', 'body_summary', 'body_summary');
        $query->addField('w', 'field_win_category_tid', 'field_win_category_tid');
        $query->addField('c', 'field_resource_category_tid', 'field_resource_category_tid');
        $query->addField('t', 'field_topics_sub_topics_tid', 'field_topics_sub_topics_tid');
        $query->addField('n', 'title', 'title');
        $query->addField('n', 'nid', 'nid');
        $query->addField('n', 'status', 'status');
        $query->addField('j', 'field_user_journey_tid', 'field_user_journey_tid');
        $query->addField('qa', 'field_is_q_a_value', 'field_is_q_a_value');
        $query->addField('m', 'field_multidocument_resource_target_id', 'field_multidocument_resource_target_id');
        $query->condition('n.status', 1);
        $query->condition('n.type', 'resource');
        $query->condition('s.field_is_secondary_resource_value', 0);
        $query->condition('j.field_user_journey_tid', $journeys, "IN");
        $query->condition('m.field_multidocument_resource_target_id', 0, '>');
        $query->distinct();
        $query->orderBy('n.created', 'ASC');
        $result = $query->execute()->fetchAll();
        $rows = [];
      
        $rows[] =['Inspire your CEO/CFO dialogue',
        'Compete to Win',
        'Lead your engagement',
        'Resource Category',
        'Collections',
        'Description',
        'URL',
        'Resource Title',
        'Know ID',
        'PDF',
        'URL'];
        foreach ($result as $key => $item) {
          if((empty($item->field_is_q_a_value) || $item->field_is_q_a_value == 0) && $item->field_user_journey_tid > 0 ) {
            $journey_tid = !empty($item->field_user_journey_tid) ? $item->field_user_journey_tid : 0;
      
            $body = $item->body_value;
            $summary = $item->body_summary;
      
            $insp = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
            $win = isset($item->field_win_category_tid) && $item->field_win_category_tid > 0 ? scf_get_term_name_from_tid($item->field_win_category_tid): '';
            $lead = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
            $rcat = isset($item->field_resource_category_tid) && $item->field_resource_category_tid > 0 ? scf_get_term_name_from_tid($item->field_resource_category_tid): '';
            $coll = $item->title;
            $desc = !empty($summary) ? $summary : $body;
            $desc = strip_tags($desc);
            $desc = str_replace('&nbsp;',' ',$desc);
            $purl = $base_url.'#/document/popup/'.$item->nid;
      
            $refnid = !empty($item->field_multidocument_resource_target_id) ? (int)$item->field_multidocument_resource_target_id : 0;
            $ref_data = [];
            $ref_data = get_ref_node_data($refnid);
            $rtitle = isset($ref_data['title']) ? $ref_data['title'] : '';
            $know = isset($ref_data['know_id']) ? $ref_data['know_id'] : '';
            $pdf = isset($ref_data['pdf']) ? $ref_data['pdf'] : '';
            $url = $base_url.'#/document/popup/'.$refnid;
      
            //win
            if($journey_tid == $win_tid) {
              $key = $journey_tid.'_'.$item->field_topics_sub_topics_tid.'_'.$item->field_win_category_tid;
      
              $lead = '';
              $insp = '';
              $win = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
              $rcat = isset($item->field_win_category_tid) && $item->field_win_category_tid > 0 ? scf_get_term_name_from_tid($item->field_win_category_tid): '';
            }
      
            //lead
            if($journey_tid == $lead_tid) {
              $key = $journey_tid.'_'.$item->field_topics_sub_topics_tid.'_'.$item->field_resource_category_tid;
              $lead = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
              $insp = '';
              $win = '';
              $rcat = isset($item->field_resource_category_tid) && $item->field_resource_category_tid > 0 ? scf_get_term_name_from_tid($item->field_resource_category_tid): '';
            }
      
            //insp
            if($journey_tid == $insp_tid) {
              $key = $journey_tid.'_'.$item->field_topics_sub_topics_tid.'_'.$item->field_resource_category_tid;
              $lead = '';
              $insp = isset($item->field_topics_sub_topics_tid) && $item->field_topics_sub_topics_tid > 0 ? scf_get_term_name_from_tid($item->field_topics_sub_topics_tid): '';
              $win = '';
              $rcat = isset($item->field_resource_category_tid) && $item->field_resource_category_tid > 0 ? scf_get_term_name_from_tid($item->field_resource_category_tid): '';
            }
      
            if(!empty($rtitle)) {
              $key = $key.'_'.$refnid;
              $rows[$key] = [
                strip_tags($insp),
                $win,
                $lead,
                $rcat,
                $coll,
                $desc,
                $purl,
                $rtitle,
                $know,
                $pdf,
                $url,
              ];
            }
          }
        }
        $filename = 'collection-report-'.time().'.csv';
        scf_reports_array_to_csv_download($rows, $filename, $delimiter=",");







      
      }
      
    
    
    
    
    

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

  }






  function get_ref_node_data($nid = 0) {
    $data = [];
    $query = \Drupal::database()->select('node_field_data', 'n');
    $query->leftjoin('field_data_field_is_secondary_resource', 's', 's.entity_id = n.nid');
    $query->leftjoin('field_data_field_is_q_a', 'qa', 'qa.entity_id = n.nid');
    $query->leftjoin('field_data_field_know_id', 'k', 'k.entity_id = n.nid');
    $query->leftjoin('field_data_field_pdf_viewer_file', 'p', 'p.entity_id = n.nid');
  
    $query->addField('p', 'field_pdf_viewer_file_fid', 'field_pdf_viewer_file_fid');
    $query->addField('k', 'field_know_id_value', 'field_know_id_value');
    $query->addField('n', 'title', 'title');
    $query->addField('n', 'nid', 'nid');
    $query->addField('n', 'status', 'status');
    $query->addField('qa', 'field_is_q_a_value', 'field_is_q_a_value');
    $query->condition('n.status', 1);
    $query->condition('n.type', 'resource');
    $query->condition('n.nid', $nid);
    $query->condition('s.field_is_secondary_resource_value', 0);
    $query->distinct();
    $result = $query->execute()->fetchAll();
    foreach ($result as $key => $item) {
      if(empty($item->field_is_q_a_value) || $item->field_is_q_a_value == 0 ) {
        $filename = '';
        $fid = (int)$item->field_pdf_viewer_file_fid;
        if($fid > 0) {
        $filename = \Drupal::database()->select('file_managed', 'f')
          ->condition('f.fid', $fid, '=')
          ->fields('f', array('filename'))
          ->execute()->fetchField();
        }
        $data = [
          'id' => $item->nid,
          'title' => $item->title,
          'know_id' => $item->field_know_id_value,
          'pdf' => $filename,
        ];
      }
    }
  
    return $data;
  }
  








}