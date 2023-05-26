<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\Form\scfp_newsletter_external_link_form.
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
 

class scfp_newsletter_external_link_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_newsletter_external.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return ' scfp_newsletter_external_link_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'scfp_newsletter_external.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_newsletter_external.settings');

    $tempstore = \Drupal::service('tempstore.private')->get('scfp_newsletter_external');
    // drupal_set_title('Newsletter External Links');
    // $weight = -50;
    // $data = [];
    // $data = get_scfp_newsletter_links();
    // $form['link_container']['#tree'] = TRUE;
    $form['scfp_newsletter_block_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Newsletter block title'),
      '#default_value' => $config->get('scfp_newsletter_block_title', ''),
      '#required' => FALSE,
      '#maxlength' => 255,
      );
    $form['scfp_newsletter_block_button_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Newsletter block bottom button text'),
      '#default_value' => $config->get('scfp_newsletter_block_button_text', ''),
      '#maxlength' => 255,
      '#required' => FALSE,
      );





      $data = [];
      $query = \Drupal::database()->select('scfp_newsletter_links_ordering', 'r');
      $query->addField('r', 'link_title', 'link_title');
      $query->addField('r', 'link_url', 'link_url');
      $query->addField('r', 'weight', 'weight');
      $query->orderBy('r.weight', 'ASC');
      // dd($query);
      $result = $query->execute()->fetchAll();
      // dd($result);
      // foreach ($result as $key => $item) {
      //   $data[] = [
      //     'link_title' => is_object($item) ? $item->link_title : '',
      //     'link_url' => is_object($item) ? $item->link_url : 0,
      //     'weight' => is_object($item) ? $item->weight : 50,
      //   ];
      // }
      // return $data;

      $data = [];
      if (!empty($result) && $result != null) {
        foreach ($result as $ke => $val) {
          $data[$ke]['id'] = $val->id;
          $data[$ke]['link_title'] = $val->link_title;
          $data[$ke]['link_url'] = $val->link_url;
          $data[$ke]['weight'] = $val->weight;
  
         
        }
  // dd($data);
        $count_data = count($data);
        // var_dump($count_data);
        // dd($count_data);
      }
      if (empty($tempstore->get('num_rows_scfp_newsletter_external')) || $tempstore->get('num_rows_scfp_newsletter_external') == null) {
        $tempstore->set('num_rows_scfp_newsletter_external', $count_data);
  
      }
  

      $form['link_container']['#tree'] = TRUE;
      for ($i = 0; $i < $tempstore->get('num_rows_scfp_newsletter_external'); $i++) {
        // $scfp_id = isset($data[$i]) ? $data[$i]['id'] : 0;
        // $remove_path = '/admin/content/manage/get-help-block/links-remove/'.$scfp_id;
        
       
        // $remove_link = 'Remove';
        // $links = '';
        // $links = '<div class="scfp-get-help-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
        // $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
        // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';
       
  
        
        $form['link_container'][$i] = [
            'scfp_newsletter_link_title' => [
               '#title' => t('Title'),
               '#type' => 'textfield',
              //  '#default_value' => $title,
              '#default_value' => !empty($data[$i]['link_title']) ? $data[$i]['link_title'] : '',
               '#prefix' => '<span>',
               '#suffix' => '</span>',
           ],

             'scfp_newsletter_link_url' => [
               '#title' => t('URL'),
               '#type' => 'textfield',
              //  '#default_value' =>  $url,
              '#default_value' => !empty($data[$i]['link_url']) ? $data[$i]['link_url'] : '',
               '#prefix' => '<span>',
               '#suffix' => '</span>',
             ],

             'weight' => [
               '#type' => 'weight',
               '#title' => t('Weight'),
              //  '#default_value' => $w,
              '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : 0,
               '#delta' => 50,
               '#title_display' => 'invisible'
             ],
           ];
          //  $weight++;
         }

  // $form['actions']['add_more'] = [
  //   '#type' => 'submit',
  //   '#value' => t('Add another link'),
  //   '#submit' => ['::scfp_newsletter_external_form_add_more'],
  //   '#ajax' => [
  //     'callback' => '::addmoreCallback',
  //     'wrapper' => 'names-fieldset-wrapper',
  //   ],
  // ];




  $form['actions']['add_name'] = [
    '#type' => 'submit',
    '#value' => $this->t('Add another link'),
    '#submit' => ['::scfp_newsletter_external_form_add_more'],
   
  ];





$form['actions']['save'] = [
  '#type' => 'submit',
  '#value' => $this->t('Save to change'),
 
];






return $form;
}

//   }




public function scfp_newsletter_external_form_add_more(array &$form, FormStateInterface $form_state) {




$tempstore = \Drupal::service('tempstore.private')->get('scfp_newsletter_external');
if (empty($tempstore->get('num_rows_scfp_newsletter_external')) || $tempstore->get('num_rows_scfp_newsletter_external') == null) {
  $tempstore->set('num_rows_scfp_newsletter_external', 1);
}else{
  $tempstore->set('num_rows_scfp_newsletter_external', $tempstore->get('num_rows_scfp_newsletter_external') + 1);
}
$form_state->setRebuild();




}

  
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // foreach($form_state['values']['link_container'] as $key => $value) {
        foreach ($form_state->getValue(['link_container']) as $key => $value) {
        $field_link = '';
        $field_link = trim($value['scfp_newsletter_link_url']);
        
        if(!empty($field_link)) {
          $field_link = strtolower($field_link);
          if (!empty($field_link) && (substr($field_link,0, 7) != 'http://') && (substr($field_link,0, 8) != 'https://')) {
            $form_state->setErrorByName('link_container]['.$key.'][scfp_newsletter_link_url', 'Invalid link. Please enter url with http or https.');
          }
        }
      }


 
    
}

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $this->config('scfp_newsletter_external.settings')
    $val = $form_state->getValues();
    // dd($val); 


    foreach ($form_state->getValues() as $key => $value) {
      $this->config('scfp_newsletter_external.settings')
          ->set($key, $value)->save();
  
  //     // parent::submitForm($form, $form_state);
  }
    
      


                
        foreach ($form_state->getValue(['link_container']) as $key => $value) {
      
        
          if(is_array ($form_state->getValue(['link_container'])) ){
            if($value['scfp_newsletter_link_title'] === "" && $value['scfp_newsletter_link_url'] === "") {
              continue;
            }
    
    // $tempstore = \Drupal::service('tempstore.private')->get('scfp_get_help');
    //   if ($items != null) {
    //     $tempstore->set('num_rows_scfp_get_help', count($items));
    //   }
  
      \Drupal::database()->insert('scfp_newsletter_links_ordering')
      // ->key(array('id' => $value['link_container']))
              // ->key(array('id' => $key))
              ->fields(array(
                'link_title' => $value['scfp_newsletter_link_title'],
                'link_url' => $value['scfp_newsletter_link_url'],
                'weight' => $value['weight'],
    
              ))
              ->execute();
          
              // }
          }
        // }
      
      }
      \Drupal::messenger()->addMessage('Form has been saved successfully');
        
  

  }





}