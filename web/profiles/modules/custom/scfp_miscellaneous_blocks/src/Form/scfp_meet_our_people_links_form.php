<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\Form\scfp_meet_our_people_links_form.
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Link;
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
use Drupal\Core\Render\Markup;





class scfp_meet_our_people_links_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return ' scfp_meet_our_people_links_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    

// $data = $this->scfp_get_meet_our_people_links();
// dd($data);



    // $refnode_default = '';
    // $refnode_default = $node->title.' [nid:'.$nid.']';
    // dd($refnode_default);



    $data = [];
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_meet_our_people');
    $query =\Drupal::database()->select('scf_meet_our_people_links', 'r');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'nid', 'nid');
    $query->addField('r', 'title', 'title');
    $query->addField('r', 'weight', 'weight');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();

    $data = [];
    if (!empty($result) && $result != null) {
      foreach ($result as $ke => $val) {
        // $data[$ke]['field_last_name_value'] = $val->field_last_name_value;
        $data[$ke]['id'] = $val->id;
        $data[$ke]['nid'] = $val->nid;
        $data[$ke]['title'] = $val->title;
        $data[$ke]['weight'] = $val->weight;

       
      }
    // dd($data);
      $count_data = count($data);
      // dd($count_data);
    }



    if (empty($tempstore->get('num_rows_scfp_meet_our_people')) || $tempstore->get('num_rows_scfp_meet_our_people') == null) {
      $tempstore->set('num_rows_scfp_meet_our_people', $count_data);

    }

    $form['link_container']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_scfp_meet_our_people'); $i++) {
      $meet_team_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      $remove_path = '/admin/meet-our-people/links-remove/'.$meet_team_id;

     


     
      $remove_link = 'Remove';
      $links = '';
      $links = '<div class="scfp-meet-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
      
      
      $title = !empty($data[$i]['n_title']) ? $data[$i]['n_titletle'] : '';
      // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';

// $num_names = $form_state->get('num_names');
// // We have to ensure that there is at least one name field.
// if ($num_names === NULL) {
//   $name_field = $form_state->set('num_names', 1);
//   $num_names = 1;
// }

// $form['#tree'] = TRUE;
// $form['names_fieldset'] = [
//   '#type' => 'fieldset',
// //   '#title' => $this->t('scfp_meet_our_people_links_form'),
//   '#prefix' => '<div id="names-fieldset-wrapper">',
//   '#suffix' => '</div>',
// ];
// // $form['names_fieldset']['is_external']= [
// //   '#type'='select',
// //   '#title' => $this->t(''),
// // ];
  


// for ($i = 0; $i < $num_names; $i++) {



  

//   $form['names_fieldset']['resource_items'][$i] = [

    $form['link_container'][$i] = [

        'id' => [
           '#title' => t('Title'),
           '#type' => 'hidden',
           '#default_value' => !empty($id) ? $id : 0,
       ],
       'link_title' => [
          '#title' => t('Title'),
          '#type' => 'textfield',
          '#default_value' => !empty($data[$i]['title']) ? $data[$i]['title'] : '',
        //   '#default_value' => $title,
      ],
        'ref_node' => [
          '#title' => t('Document'),
          '#type' => 'textfield',
          '#default_value' => !empty($data[$i]['nid']) ? $title . ' ' .'  [ '.'nid:'.$data[$i]['nid'].' ] ' : '',
          
          // '#default_value' =>  $refnode_default,
          // '#autocomplete_path' => 'admin/meet-our-people/autocomplete',
          '#autocomplete_route_name' => 'scfp_miscellaneous_blocks.scfp_meet_our_people_links_autocomplete',
        ],
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          // '#default_value' => $w,
          '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : -0,
          '#delta' => 50,
          '#title_display' => 'invisible'
        ],
        'links' => [
          '#type' => 'item',
          '#markup' => $links,
        ],
  ];
      $weight++;
  }







  $form['actions']['add_more'] = [
    '#type' => 'submit',
    '#value' => t('Add another link'),
    '#submit' => ['::scfp_meet_our_people_links_form_add_more'],
    // '#ajax' => [
    //   'callback' => '::addmoreCallback',
    //   'wrapper' => 'names-fieldset-wrapper',
    // ],
  ];


$form['actions']['save'] = [
  '#type' => 'submit',
  '#value' => $this->t('Save to change'),
 
];





// if ($num_names > 1) {
//   $form['names_fieldset']['actions']['remove_name'] = [
//     '#type' => 'submit',
//     '#value' => $this->t('Remove one'),
//     '#submit' => ['::removeCallback'],
//     '#ajax' => [
//       'callback' => '::addmoreCallback',
//       'wrapper' => 'names-fieldset-wrapper',
//     ],
//   ];
// }

// }
return $form;
}

//   }


// public function addmoreCallback(array &$form, FormStateInterface $form_state) {
// return $form['names_fieldset'];
// }


public function scfp_meet_our_people_links_form_add_more(array &$form, FormStateInterface $form_state) {
// $name_field = $form_state->get('num_names');
// $add_button = $name_field + 1;
// $form_state->set('num_names', $add_button);

// $form_state->setRebuild();

  $tempstore = \Drupal::service('tempstore.private')->get('scfp_meet_our_people');
  if (empty($tempstore->get('num_rows_scfp_meet_our_people')) || $tempstore->get('num_rows_scfp_meet_our_people') == null) {
  $tempstore->set('num_rows_scfp_meet_our_people', 1);
   }else{
  $tempstore->set('num_rows_scfp_meet_our_people', $tempstore->get('num_rows_scfp_meet_our_people') + 1);
   }
    $form_state->setRebuild();


    }



  public function validateForm(array &$form, FormStateInterface $form_state) {
 
    
}

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    //   foreach ($form_state->getValue(['names_fieldset', 'resource_items']) as $id => $item) {






      $val = $form_state->getValues();
      // dd($val);
 
        // db_truncate('scf_meet_our_people_links')->execute();
        if(is_array($form_state->getValue([ 'link_container']))){
        //   foreach ($form_state['values']['link_container'] as $key => $value) {
            foreach ($form_state->getValue([ 'link_container']) as $key => $value) {
// dd($value);
            if(is_array($form_state->getValue([ 'link_container']))) {
              if(!empty($value['link_title']) && !empty($value['ref_node'])) {
                $matches = array();
                $result = preg_match('/\[.*\:(\d+)\]$/', $value['ref_node'], $matches);
                $nid = isset($matches[$result]) ? $matches[$result] : 0;
                // dd($nid);
                if($nid > 0) {
                \Drupal::database()->insert('scf_meet_our_people_links')
                  ->fields(array(
                    'title' => $value['link_title'],
                    'nid' => $nid,
                    'weight' => $value['weight'],
                ))
                
                ->execute();
              }
             }
            }
          }
        }
        \Drupal::messenger()->addMessage('Form has been saved successfully');

  }


/**
 * Function to get all links data.
 */
function scfp_get_meet_our_people_links(){
    $data = [];
    $query = \Drupal::database()->select('scf_meet_our_people_links', 'r');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'nid', 'nid');
    $query->addField('r', 'title', 'title');
    $query->addField('r', 'weight', 'weight');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();
    foreach ($result as $key => $item) {
      $data[] = [
        'id' => is_object($item) ? $item->id : 0,
        'nid' => is_object($item) ? $item->nid : 0,
        'title' => is_object($item) ? $item->title : "",
        'weight' => is_object($item) ? $item->weight : 50,
      ];
    }
    return $data;
  }
  
  /**
  * autocomplete_get_involved
  * @return JSON
  */
  function scfp_get_meet_our_people_links_autocomplete() {
    $string = arg(3);
    $matches = array();
    if ($string) {
      $query = \Drupal::database()->select('node', 'n');
      $query->fields('n', ['nid', 'title'])
        ->condition('n.type', 'resource', '=')
        ->condition('n.status', 1, '=')
        ->condition('n.title', '%' . db_like($string) . '%', 'LIKE')
        ->range(0, 20);
      $items = $query->execute();
      foreach ($items as $item) {
          $matches[$item->title.' [nid:'.$item->nid.']'] = check_plain($item->title).' [nid:'.$item->nid.']';
      }
    }
    drupal_json_output($matches);
  }
  
  /**
   * remove_item_from_meet_our_people_links
   * @param  integer $nid node id
   */
  public function remove_item_from_meet_our_people_links($id = 0) {
    \Drupal::database()->delete('scf_meet_our_people_links')
    ->condition('id', $id)
    ->execute();
  $redirect =new RedirectResponse(drupal_get_destination());
    
        $redirect->send();



  }
  




}