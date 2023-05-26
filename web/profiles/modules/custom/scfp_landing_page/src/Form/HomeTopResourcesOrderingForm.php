<?php
/**
 * @file
 * Contains \Drupal\scfp_landing_page\Form\HomeTopResourcesOrderingForm.
 */
namespace Drupal\scfp_landing_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Link;
use Drupal\file\Entity\File;
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

 




class HomeTopResourcesOrderingForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'home_top_resources_orderingf_orm';
  }
  



  
  public function buildForm(array $form, FormStateInterface $form_state) {

    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');

    $query = \Drupal::database()->select('scf_home_page_top_resources_ordering', 'r');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('r', 'title', 'title');
    $query->addField('r', 'link', 'link');
    $query->addField('r', 'enabled', 'enabled');
    $query->orderBy('r.id', 'ASC');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();
  
    $data = [];
    if (!empty($result) && $result != null) {
      foreach ($result as $ke => $val) {
        $data[$ke]['id'] = $val->id;
        $data[$ke]['title'] = $val->title;
        $data[$ke]['link'] = $val->link;
        $data[$ke]['weight'] = $val->weight;
        $data[$ke]['is_selected'] = $val->enabled;
       
      }

      $count_data = count($data);
    }

    if (empty($tempstore->get('num_rows_resources_page')) || $tempstore->get('num_rows_resources_page') == null) {
      $tempstore->set('num_rows_resources_page', $count_data);

    }

    $form['resource_items']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_resources_page'); $i++) {
      $resource_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      $remove_path = '/admin/managing/home-page-top-resources-remove/'.$resource_id;
     
      $remove_link = 'Remove';
      $links = '';
      $links = '<div class="home-page-hot-topics-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';

      $form['resource_items'][$i] = [

        'is_selected' => [
          '#type' => 'checkbox',
          '#title' => '&nbsp;',
          '#default_value' => isset($data[$i]) ? $data[$i]['is_selected'] : 1,
          '#disabled' => $attr_checkbox,
        ],
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          '#default_value' => isset($data[$i]) ? $data[$i]['weight'] : '',
          '#delta' => 50,
          '#title_display' => 'invisible',
        ],
        'title' =>[
          '#type' => 'textfield',
          '#default_value' => isset($data[$i]) ? $data[$i]['title'] : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#required' => FALSE,

        ],
        'resource_id' => [
          '#type' => 'hidden',
          '#default_value' => isset($data[$i]) ? $data[$i]['id'] : 0,
        ],
        'link' =>[
          '#type' => 'textfield',
          '#default_value' => isset($data[$i]) ? $data[$i]['link'] : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#required' => FALSE,
        ],

        'links' => [
          '#type' => 'item',
          '#markup' => $links,
        ],
      ];
    }

    $form['names_fieldset']['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
    );
   
    $form['names_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another top resource'),
      '#submit' => ['::home_top_resources_ordering_form_add_more'],
     
    ];
    
   return $form;

  }

  function home_top_resources_ordering_form_add_more(array &$form, FormStateInterface $form_state)
  { 
   
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');

    if (empty($tempstore->get('num_rows_resources_page')) || $tempstore->get('num_rows_resources_page') == null) {
      $tempstore->set('num_rows_resources_page', 1);
    }else{
      $tempstore->set('num_rows_resources_page', $tempstore->get('num_rows_resources_page') + 1);
    }
    $form_state->setRebuild();


  }


  
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $val= $form_state->getValues();
    foreach($val['resource_items'] as $key => $item) {
      if(!empty($item['title']) ||  !empty($item['link'])) {
        $items[] = ['title' => $item['title'], 'is_selected' => $item['is_selected'],'link' =>  $item['link'],'weight' =>  $item['weight'],'resource_id' =>  $item['resource_id']];     
      }
    }

    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');
      
    $tempstore->set('num_rows_resources_page', count($items));
    foreach($items as $key => $item) {
      
      if($item['resource_id'] > 0 ){
        \Drupal::database()->update('scf_home_page_top_resources_ordering')
       ->fields(array(
        'title' => $item['title'],
        'link' => $item['link'],
        'weight' => $item['weight'],
        'enabled' => $item['is_selected'],
      ))
      ->condition('id', $item['resource_id'])
      ->execute();
    
      } else {
        $query = \Drupal::database()->insert('scf_home_page_top_resources_ordering'); // Table name no longer needs {}
        $query->fields(array(
          'title' => $item['title'],
          'link' => $item['link'],
          'weight' => $item['weight'],
          'enabled' => $item['is_selected'],
        ))->execute();
    
      }

    }
    \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');



    
  }


}