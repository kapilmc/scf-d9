<?php
/**
 * @file
 * Contains \Drupal\scfp_landing_page\Form\HomeNewsOrderingForm.
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
use Drupal\image\Entity\ImageStyle;
use Drupal\media\Entity\Media;

class HomeNewsOrderingForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return ' home_news_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');

    $query = \Drupal::database()->select('scf_home_page_news_ordering', 'r');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('r', 'fid', 'fid');
    $query->addField('r', 'link', 'link');
    $query->addField('r', 'enabled', 'enabled');
    $query->addField('r', 'external', 'external');
    $query->orderBy('r.id', 'DESC');
    $result = $query->execute()->fetchAll();
    


    $data = [];
    if (!empty($result) && $result != null) {
      foreach ($result as $ke => $val) {
        $data[$ke]['id'] = $val->id;
        $data[$ke]['fid'] = $val->fid;
        $data[$ke]['link'] = $val->link;
        $data[$ke]['external'] = $val->external;
        $data[$ke]['weight'] = $val->weight;
        $data[$ke]['is_selected'] = $val->enabled;
       
      }

      $count_data = count($data);
    }
    // dump($data);
    if (empty($tempstore->get('num_rows_news_page')) || $tempstore->get('num_rows_news_page') == null) {
      $tempstore->set('num_rows_news_page', $count_data);

    }

    $form['news_items']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_news_page'); $i++) {
     
      if ($data[$i]['external'] != null && $data[$i]['external'] != '') {
        switch ($data[$i]['external']) {
          case 0:
            // $is_external = 'Internal Link';
            $options = array(0 => 'Internal Link', 1 => 'External Link', 2 => 'Mail');
            break;
          case 1:
            // $is_external = 'External Link';
            $options = array(1 => 'External Link', 0 => 'Internal Link', 2 => 'Mail');
            break;
          case 2:
            // $is_external = 'Mail';
            $options = array(2 => 'Mail',0 => 'Internal Link', 1 => 'External Link');
            break;
        }
      }else{
        $options = array(0 => 'Internal Link', 1 => 'External Link', 2 => 'Mail');
      }

      $news_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      $remove_path = '/admin/managing/home-page-news-remove/'.$news_id;
     
      $remove_link = 'Remove';
      $links = '';
      $links = '<div class="home-page-hot-topics-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';

      $form['news_items'][$i] = [
        'is_selected' => [
          '#type' => 'checkbox',
          '#title' => '&nbsp;',
          '#default_value' => isset($data[$i]) ? $data[$i]['is_selected'] : 1,
          // '#disabled' => $attr_checkbox,
        ],
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          '#default_value' => isset($data[$i]) ? $data[$i]['weight'] : '',
          '#delta' => 50,
          '#title_display' => 'invisible',
        ],
        'link' =>[
          '#type' => 'textfield',
          '#default_value' => isset($data[$i]) ? $data[$i]['link'] : '',
          '#size' => 60,
          '#maxlength' => 255,
          '#required' => FALSE,
        ],
        'news_id' => [
          '#type' => 'hidden',
          '#default_value' => isset($data[$i]) ? $data[$i]['id'] : 0,
        ],
        'resource' => [
          '#type' => 'managed_file',
          '#title' => t('Image'),
          '#default_value' =>  isset($data[$i]['fid']) ? [$data[$i]['fid']] : '',
          '#upload_location' => 'public://scfp_home_page_news/',
          "#upload_validators"  => array("file_validate_extensions" => array("png gif jpg jpeg")),
          'file_validate_size' => array(25600000),
          //'#attributes' => $attr,
        ],
        'is_external' => [
          '#type' => 'select',
          // '#title' => '&nbsp;',
          '#options' => $options,
          '#default_value' => isset($is_external) ? $is_external : '',
          // '#disabled' => $attr_checkbox,
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
      '#value' => $this->t('Add another news'),
      '#submit' => ['::home_top_news_ordering_form_add_more'],
     
    ];
  
   return $form;

  }


  function home_top_news_ordering_form_add_more(array &$form, FormStateInterface $form_state)
  { 
   
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');
    if (empty($tempstore->get('num_rows_news_page')) || $tempstore->get('num_rows_news_page') == null) {
      $tempstore->set('num_rows_news_page', 1);
    }else{
      $tempstore->set('num_rows_news_page', $tempstore->get('num_rows_news_page') + 1);
    }
    $form_state->setRebuild();


  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
    $val = $form_state->getValues();
    foreach ($form_state->getValues()['news_items'] as $id => $item) {
      if (empty($item['link'])) {
        $form_state->setErrorByName('link', $this->t('Link field is required.'));
      }
      if (count($item['resource']) == 0 || $item['resource'] == null) {
        $form_state->setErrorByName('resource', $this->t('Image field is required.'));
      }
    }
    
    
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $val = $form_state->getValues();
    foreach($val['news_items'] as $key => $item) {
     
      if(!empty($item['is_external']) ||  !empty($item['link']) && !empty($item['resource'])) {
        $items[] = ['resource' => $item['resource'][0], 'is_selected' => $item['is_selected'],'link' =>  $item['link'],'weight' =>  $item['weight'],'news_id' =>  $item['news_id'],'is_external' =>  $item['is_external']];     
      }
    }
  
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_landing_page');
    if ($items != null) {
      $tempstore->set('num_rows_news_page', count($items));
    }
  
    foreach($items as $key => $item) {
      if($item['news_id'] > 0 ){
        \Drupal::database()->update('scf_home_page_news_ordering')
       ->fields(array(
        'fid' => $item['resource'],
        'link' => $item['link'],
        'weight' => $item['weight'],
        'enabled' => $item['is_selected'],
        'external' => $item['is_external'],
      ))
      ->condition('id', $item['news_id'])
      ->execute();
    
      } else {
        $query = \Drupal::database()->insert('scf_home_page_news_ordering')
        ->fields(array(
          'fid' => $item['resource'],
          'link' => $item['link'],
          'weight' => $item['weight'],
          'enabled' => $item['is_selected'],
          'external' => $item['is_external'],
        ))->execute();
    
      }

    }
    \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');

  }




}