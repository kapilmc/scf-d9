<?php
 
/**
 * @file
 * @Contains Drupal\scf_hit_page\Controller\HitPageController.
 */

namespace Drupal\scf_hit_page\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AddCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Form\FormState;
use Drupal\user\PrivateTempStoreFactory;
use Drupal\Core\Render\Markup;

/**
 * Implement Demo class operations.
 */
class HitPageController extends ControllerBase {
  // $form=\Drupal::formBuilder()->getForm(Drupal\demo\Form\SearchForm);

  /**
  * remove_item_from_hot_topics
  * @param  integer $nid node id
  */
    function remove_hit_page_quotes_form($id = 0) {

        $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
        $config = \Drupal::configFactory()->getEditable('hit_page_quotes.settings');
        $count = $config->get('hit_page_quotes_items');
        $count = ($count == '' || $count == 0) ? 0 : $count;

        $items = [];

        for ($i = 0; $i < $count; $i++) {
            if($id == $i) {
                $config->clear('hit_page_quotes_item_title_'.$i);
                $config->clear('hit_page_sub_title_'.$i);
                $config->clear('hit_page_quotes_item_descrition_'.$i);

            }
             else {
    
                if (!empty($config->get('hit_page_quotes_item_title_'.$i)) || !empty($config->get('hit_page_sub_title_'.$i)) || !empty($config->get('hit_page_quotes_item_descrition_'.$i)['value'])) {
                    $items[] = ['title' => $config->get('hit_page_quotes_item_title_'.$i), 'sub_title' => $config->get('hit_page_sub_title_'.$i),'text' =>  $config->get('hit_page_quotes_item_descrition_'.$i)]; 
                }
                
            }

        
        }
    
  
        $tempstore->set('num_rows', count($items));
        $config->set('hit_page_quotes_items',  count($items));
     
       
        foreach($items as $key => $item) {
        
            // Set the submitted configuration setting.
            $config->set('hit_page_quotes_item_title_'.$key, $item['title']);
            $config->set('hit_page_sub_title_'.$key, $item['sub_title']);
            $config->set('hit_page_quotes_item_descrition_'.$key, $item['text']);
            $config->set('hit_page_quotes_items', count($items));
            $config->save();
    
        }
        if (count($items) == 0) {
           $config->save();
        } 

       

        $redirect =new RedirectResponse('/admin/hit-page/quotes');
        $redirect->send();
   
    }




    function remove_hit_page_sample_hits_form($id = 0) {

      

      $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
  
      $config = \Drupal::configFactory()->getEditable('hit_page_sample_hits.settings');
     
      $count = $config->get('hit_page_sample_items');
      $count = ($count == '' || $count == 0) ? 0 : $count;

      $items = [];

      for ($i = 0; $i < $count; $i++) {
          if($id == $i) {
              $config->clear('hit_page_sample_hits_item_title'.$i);
              $config->clear('hit_page_sample_hits_item_descrition'.$i);
           

              
 
          }
           else {
              
              if (!empty($config->get('hit_page_sample_hits_item_title'.$i)) || !empty($config->get('hit_page_sample_hits_item_descrition'.$i))) {
                  $items[] = ['title' => $config->get('hit_page_sample_hits_item_title'.$i), 'url' => $config->get('hit_page_sample_hits_item_descrition'.$i)]; 
              }
              
          }

      
      }
      
      
      
          $tempstore->set('num_rows_sample', count($items));
          $config->set('hit_page_sample_items', count($items));
   
     
      foreach($items as $key => $item) {
      
          // Set the submitted configuration setting.
          $config->set('hit_page_sample_hits_item_title'.$key, $item['title']);
          $config->set('hit_page_sample_hits_item_descrition'.$key, $item['url']);
          $config->set('hit_page_sample_items', count($items));
          $config->save();
  
      }
      if (count($items) == 0) {
         $config->save();
      } 

     

      $redirect =new RedirectResponse('/admin/hit-page/sample-hits');
      $redirect->send();
 
  }







  //hitpage coreteam
  public function autocomplete_item_core_team_hit(Request $request) {
    
    $string = \Drupal::request()->query->get('q');
    $matches = array();
    if ($string) {
      $data = [];
      $connection = \Drupal\Core\Database\Database::getConnection();
      $query = $connection->select('scf_hit_team_ordering', 'r');
      $query->join('node', 'n', 'r.nid = n.nid');
      $query->addField('r', 'nid', 'nid');
      $query->condition('r.key_contact', 0);
      $result = $query->execute()->fetchAll();
      foreach ($result as $key => $item) {
          $data[] = $item->nid;
      
      }


      $query = $connection->select('node_field_data', 'n');
      $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
      $query->leftjoin('node__field_expert_search', 'f', 'f.entity_id = n.nid');
      $query->fields('l', ['field_last_name_value', 'field_last_name_value']);
      $query->fields('f', ['field_expert_search_value', 'field_expert_search_value']);
      $query->fields('n', ['nid', 'nid']);
      $query->fields('n', ['title', 'title']);
      $query->condition('n.type', ['experts'], 'IN');
      $query->condition('f.field_expert_search_value', $connection->escapeLike($string).'%', 'LIKE');
      $items = $query->execute()->fetchAll();
      // dd($items);
  

      $sort_array = array();
      foreach ($items as $item) {
        $fname = is_object($item) ? $item->title : '';
        $lname = is_object($item) ? $item->field_last_name_value : '';
        $sort_array[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;
      }

      // Sort topics by title and leave only 10 of them.
      asort($sort_array);
      $sort_array = array_slice($sort_array, 0,20, TRUE);
      // dd($sort_array);
      foreach ($sort_array as $key => $value) {

        $check_value = \Drupal\Component\Utility\Html::escape($value);
        $matches[] = ['value'=>$key,'label'=>$check_value];
      }
        
          
    }
        

        
        return new JsonResponse($matches);
  }



         /**
 * Remove_item_hit_core_team.
 *
 * @param int $id
 */
  function remove_item_hit_core_team($id = 0) {
    $query = \Drupal::database()->delete('scf_hit_team_ordering');
    $query->condition('id', $id);
    $query->execute();

    $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
    $tempstore->set('num_rows_core_team_page', $tempstore->get('num_rows_core_team_page') - 1);

    $redirect =new RedirectResponse('/admin/hit-page/core-team');
    $redirect->send();
    
      
  }  
  
           /**
 * Remove_item_hit_core_team.
 *
 * @param int $id
 */
function remove_item_hit_page_key_contact($id = 0) {
  $query = \Drupal::database()->delete('scf_hit_team_ordering');
  $query->condition('id', $id);
  $query->execute();

  $tempstore = \Drupal::service('tempstore.private')->get('scf_hit_page');
  $tempstore->set('num_rows_key_contact_page', $tempstore->get('num_rows_key_contact_page') - 1);

  $redirect =new RedirectResponse('/admin/hit-page/core-team/key-contact');
  $redirect->send();
  
    
} 

  



  /**
   * Autocomplete_item_from_hot_topics.
   *
   * @return JSON
   */
  function autocomplete_item_key_conatct_hit()
  {
    $string = \Drupal::request()->query->get('q');

    $matches = array();
    if ($string) {
      $data = [];
      $connection = \Drupal\Core\Database\Database::getConnection();
      $query = $connection->select('scf_hit_team_ordering', 'r');
      $query->join('node', 'n', 'r.nid = n.nid');
      $query->addField('r', 'nid', 'nid');
      $query->condition('r.key_contact', 1);
      $result = $query->execute()->fetchAll();
      foreach ($result as $key => $item) {
          $data[] = $item->nid;
      
      }

      $query = $connection->select('node_field_data', 'n');
      $query->leftjoin('field_data_field_last_name', 'l', 'l.entity_id = n.nid');
      $query->leftjoin('node__field_expert_search', 'f', 'f.entity_id = n.nid');
      $query->fields('l', ['field_last_name_value', 'field_last_name_value']);
      $query->fields('f', ['field_expert_search_value', 'field_expert_search_value']);
      $query->fields('n', ['nid', 'nid']);
      $query->fields('n', ['title', 'title']);
      $query->condition('n.type', ['experts'], 'IN');
      $query->condition('f.field_expert_search_value', $connection->escapeLike($string).'%', 'LIKE');

      $items = $query->execute()->fetchAll();
      $sort_array = array();
      foreach ($items as $item) {
        $fname = is_object($item) ? $item->title : '';
        $lname = is_object($item) ? $item->field_last_name_value : '';
        $sort_array[$fname . ' ' . $lname . ' [nid:' . $item->nid . ']'] = $fname . ' ' . $lname;
      }

      // Sort topics by title and leave only 10 of them.
      asort($sort_array);
      $sort_array = array_slice($sort_array, TRUE);
      foreach ($sort_array as $key => $value) {
        $check_value = \Drupal\Component\Utility\Html::escape($value);
        $matches[] = ['value'=>$key,'label'=>$check_value];
      }
    }
    return new JsonResponse($matches);


  }

}



