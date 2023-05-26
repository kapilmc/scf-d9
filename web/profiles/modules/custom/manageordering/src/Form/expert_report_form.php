<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\expert_report_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

class expert_report_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'expert_report_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {


    $config = $this->config('scfp_meet_our_people_tab.settings');
    $fa = $config->get('meet_our_people_first_alert', 0);
    $vid = 'expert_category';
  $tag_terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  // $options = $options = [] +[];
  foreach ($tag_terms as $tag_term) {
  $options[$tag_term->tid] = $tag_term->name;
  }
    // $fa = $this->variable_get('meet_our_people_first_alert', 0);
  
    // $cat_tid = @arg(3);
    if($cat_tid == 0) {
      $cat_tid = $fa;
    }
  
    $cat_options = $options;
    // $rows = $this->_get_expert_report_list($cat_tid, TRUE);
    // dd($rows);
    
    $form['cat'] = [
      '#type' => 'select',
      '#title' => t('Choose Expert category'),
      '#options' => $cat_options,
      '#default_value' => (int)$cat_tid,
      '#attributes' => array('onchange' => "form.submit('cat')"),
    ];
    $form['actions']['redirect'] = array(
       '#type' => 'submit',
       '#value' => t('Submit'),
       '#submit' => array([$this,'_expert_report_form_submit']),
       '#prefix' => '<div style="display:none;">',
       '#suffix' => '</div>',
     );
     $form['actions']['download_all'] = array(
        '#type' => 'submit',
        '#value' => t('Download CSV'),
        '#submit' => array([$this,'_expert_report_download']),
      );
    return $form;
  }
  
  // /**
  //  * expert_report_list
  //  */
  function expert_report_list() {

    // $fa = variable_get('meet_our_people_first_alert', 0);
    $fa = $config->get('meet_our_people_first_alert', 0);

    // $cat_tid = @arg(3);
    if($cat_tid == 0) {
      $cat_tid = $fa;
    }
  
    $output = '';
    $output .= drupal_render(drupal_get_form('expert_report_form'));
    $header = ['FMNO','Name', 'Email', 'Role', 'Unique role', 'Focus area', 'Industry', 'Topics', 'Tools'];
    $rows = $this->_get_expert_report_list($cat_tid, TRUE);
    $output .= '<br/><div class="custom-table-view">';
    $output .= theme('table',
      array(
        "header" => $header,
        "rows" => $rows,
        "sticky" => FALSE,
        "empty" =>t('There are no data'),
      )
    );
    $output .= '</div>';
    // Show the pager
    $output .= theme('pager');
    return $output;
  }
  
  /**
   * _expert_report_form_submit
   */
  function _expert_report_form_submit(&$form, &$form_state) {
      // $val = $form_state['values'];

      $val = $form_state->getValues();
      $cat = isset($val['cat']) ? $val['cat'] : 0;
      // $path = 'admin/admin-dashboard/expert-report/'.$cat;
      // $form_state['redirect'] = $path;
  
    
      $redirect =new RedirectResponse('/admin/admin-dashboard/expert-report/'.$cat);
      $redirect->send();
  
  
  
  
  }
  
  /**
   * _expert_report_download
   */
  function _expert_report_download(&$form, &$form_state) {
    // dd('csv download');
    
    $val = $form_state->getValues();
    // $val = $form_state['values'];
    $cat = isset($val['cat']) ? $val['cat'] : 0;
    $header[] = ['FMNO', 'Name', 'Email', 'Role', 'Unique role', 'Focus area', 'Industry', 'Topics', 'Tools'];
    $rows = array_merge($header,$this->_get_expert_report_list($cat));
    $cat_name = $this->scf_get_term_name_from_tid($cat);
    $filename = 'expert-report-'.$cat_name.'-'.time().'.csv';
    // scf_reports_array_to_csv_download($rows, $filename, $delimiter=",");
  }
  
  /**
   * _get_expert_report_list
   * @param  integer $cat
   * @param  boolean $pager
   * @return Array
   */
  function _get_expert_report_list($cat = 0, $pager = FALSE) {
    $data = [];
    // if($cat > 0) {
    //   if($pager) {
    //     $query = \Drupal::database()->select('scf_expert_ordering', 'r')->extend('PagerDefault');
    //   } else {
        $query = \Drupal::database()->select('scf_expert_ordering', 'r');
      // }
      $query->join('node_field_data', 'n', 'r.nid = n.nid');
      $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
      $query->leftjoin('node_revision__field_is_external_expert', 'e', 'e.entity_id = n.nid');
      // node_revision__comment_node_experts /// field_data_field_is_external_expert
      $query->leftjoin('node_revision__field_expert_role', 'er', 'er.entity_id = n.nid');
      // node_revision__field_expert_role
      $query->leftjoin('node__field_unique_role', 'ur', 'ur.entity_id = n.nid');
      // node__field_unique_role
      $query->leftjoin('node_revision__field_core_sectors', 'fa', 'fa.entity_id = n.nid'); 
      // node_revision__field_core_sectors
      $query->leftjoin('node_revision__field_email', 'em', 'em.entity_id = n.nid');
      // node_revision__field_email
      $query->leftjoin('node_revision__field_fmno', 'fm', 'fm.entity_id = n.nid');
      // node_revision__field_fmno
      $query->addField('r', 'nid', 'id');
      $query->addField('n', 'title', 'title');
      $query->addField('l', 'field_last_name_value', 'field_last_name_value');
      $query->addField('e', 'field_is_external_expert_value', 'field_is_external_expert_value');
      $query->addField('er', 'field_expert_role_target_id', 'field_expert_role_target_id');
      $query->addField('ur', 'field_unique_role_value', 'field_unique_role_value');
      $query->addField('fa', 'field_core_sectors_value', 'field_core_sectors_value');
      $query->addField('em', 'field_email_value', 'field_email_value');
      $query->addField('fm', 'field_fmno_value', 'field_fmno_value');
      $query->condition('n.status', 1);
      $query->condition('r.cat', $cat);
      $query->condition('r.topic', 0);
      $query->condition('r.inspire', 0);
      if($pager) {
        // $query->limit(50);
        // $query->range(0, 49);
      }
      $query->orderBy('l.field_last_name_value', 'ASC');

      
      $result = $query->execute()->fetchAll();
      dd($result);
      foreach ($result as $key => $item) {
        $fname = is_object($item) ? $item->title : '';
        $lname = is_object($item) ? $item->field_last_name_value : '';
        $role = is_object($item) ? $item->field_is_external_expert_value : 0;
        if($role == 1 ) {
          $role_title = 'External Advisors';
        } else {
          $role_id = is_object($item) ? $item->field_expert_role_tid : 0;
          $role_title = scf_get_term_name_from_tid($role_id);
        }
  
        $industry = _scf_expert_report_get_industry($item->id);
        $tools =_scf_expert_report_get_tools($item->id);
        $topics = _scf_expert_report_get_topics($item->id, $cat);
        $topics_array = [];
        $topics_array_temp = @explode(",", $topics);
        $topics_array = array_filter($topics_array_temp);
        if(!empty($topics_array)) {
          foreach($topics_array as $topic) {
            $data[] = ['fmno' => $item->field_fmno_value,
                      'title' => $fname .' '.$lname,
                      'email' => $item->field_email_value,
                      'role' => $role_title,
                      'unique_role' => $item->field_unique_role_value,
                      'focus_area' => $item->field_core_sectors_value,
                      'industry' => $industry,
                      'topic' => $topic,
                      'tools' => $tools,
                      ];
            }
          } else {
            $data[] = ['fmno' => $item->field_fmno_value,
                      'title' => $fname .' '.$lname,
                      'email' => $item->field_email_value,
                      'role' => $role_title,
                      'unique_role' => $item->field_unique_role_value,
                      'focus_area' => $item->field_core_sectors_value,
                      'industry' => $industry,
                      'topic' => '',
                      'tools' => $tools,
                      ];
          }
       }
      // }
      return $data;
  }
  
  function _scf_expert_report_get_industry($nid = 0) {
    $industry = [];
    $query = \Drupal::database()->select('field_data_field_map_industry', 'r');
    $query->leftjoin('taxonomy_term_data', 't', 't.tid = r.field_map_industry_tid');
    $query->addField('t', 'name', 'name');
    $query->condition('r.entity_id', $nid);
    $result = $query->execute()->fetchAll();
    if(!empty($result)) {
      $nn = json_decode(json_encode($result),TRUE);
      $industry = array_column($nn, 'name');
    }
    return implode(", ",$industry);
  }
  
  function _scf_expert_report_get_tools($nid = 0) {
    $industry = [];
    $query = \Drupal::database()->select('field_data_field_expert_tools', 'r');
    $query->leftjoin('node', 'n', 'n.nid = r.field_expert_tools_target_id');
    $query->addField('n', 'title', 'title');
    $query->condition('r.entity_id', $nid);
    $query->condition('n.status', 1);
    $query->condition('r.bundle', 'experts');
    $result = $query->execute()->fetchAll();
    if(!empty($result)) {
      $nn = json_decode(json_encode($result),TRUE);
      $industry = array_column($nn, 'title');
    }
    return implode(", ",$industry);
  }
  
  function _scf_expert_report_get_topics($nid = 0, $cat = 0) {
    $mappings = \Drupal::database()->select('scf_experts_mapping', 'k')
          ->fields('k', ['mapping'])
          ->condition('k.nid', $nid)
          ->execute()
          ->fetchField();
    $map = $mapping = [];
    $exper_cat_topics = [];
    $parent_topics =  get_all_terms_by_parents_terms_vocab('topics');
    $mappings = unserialize($mappings);
    $mapping = $mappings[$cat];
      foreach($mapping as $val) {
        if(!in_array($val['tid'], $parent_topics)) {
          $map[$cat][] = scf_get_term_name_from_tid($val['tid']);
        }
      }
  
      if(isset($map[$cat]) && !empty($map[$cat])) {
        $exper_cat_topics = array_unique($map[$cat]);
      }
   return implode(", ", $exper_cat_topics);
  }


 public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {


}

}




