<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_newsletter_form.
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
 

class scfp_newsletter_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_newsletter.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_newsletter_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
   return ['scfp_newsletter.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_newsletter.settings');
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_newsletter');
  
    // drupal_set_title('Manage Newsletters');
    // $weight = -50;
    // $data = [];
    // $form['link_container']['#tree'] = TRUE;
    // $data = get_newsletter_data();
  
    $form['scfp_newsletter_subscription_submit'] = array(
      '#type' => 'textarea',
      '#title' => t('Newsletter Subscribed Text'),
      '#default_value' => $config->get('scfp_newsletter_subscription_submit', 'Subscription successful'),
      '#rows' => 2,
    );
    $form['scfp_newsletter_subscription_email'] = array(
      '#type' => 'textarea',
      '#title' => t('Newsletter Subscription Email Template'),
      '#default_value' => $config->get('scfp_newsletter_subscription_email', ''),
      '#rows' => 3,
    );
  
    // if (empty($form_state['num_rows'])) {
    //   $form_state['num_rows'] = count($data);
    // }
  
    // for($x = 0; $x < $form_state['num_rows']; $x++) {
  
    //   $title = $url = $w = '';
    //   if (array_key_exists($x,$data)) {
    //     $title = $data[$x]['link_title'];
    //     $url = $data[$x]['link_url'];
    //     $w = $data[$x]['weight'] ;
    //     $tooltip = $data[$x]['tooltip'] ;
    //   }
    //   else {
    //     if(array_key_exists($x-1,$data)) {
    //       $w = $data[$x-1]['weight'] + 1;
    //     }
  
    //   }




    $data = [];
    $query = \Drupal::database()->select('scfp_newsletter_ordering', 'r');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'newsletter_title', 'link_title');
    $query->addField('r', 'newsletter_email', 'link_url');
    $query->addField('r', 'newsletter_tooltip', 'tooltip');
    $query->addField('r', 'weight', 'weight');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();
    // foreach ($result as $key => $item) {
    //   $data[] = [
    //     // 'id' => is_object($item) ? $item->id : 0,
    //     'link_title' => is_object($item) ? $item->link_title : '',
    //     'link_url' => is_object($item) ? $item->link_url : 0,
    //     'tooltip' => is_object($item) ? $item->tooltip : 0,
    //     'weight' => is_object($item) ? $item->weight : 50,
    //   ];
    // }
    // dd($data);
    // return $data;





    $data = [];
    if (!empty($result) && $result != null) {
      foreach ($result as $ke => $val) {
        $data[$ke]['id'] = $val->id;
        $data[$ke]['link_title'] = $val->link_title;
        $data[$ke]['link_url'] = $val->link_url;
        $data[$ke]['tooltip'] = $val->tooltip;
        $data[$ke]['weight'] = $val->weight;

       
      }

      $count_data = count($data);
      var_dump($count_data);
      // dd($count_data);
    }
    if (empty($tempstore->get('num_rows_scfp_newsletter')) || $tempstore->get('num_rows_scfp_newsletter') == null) {
      $tempstore->set('num_rows_scfp_newsletter', $count_data);

    }



















    $help_text = 'Newsletter Below';
    $header = array(t('Newsletter'), '','', t('Weight'));
        
    $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        // 'caption' => $help_text,
        // '#rows' => $data,
        // '#empty' => $this->t('No data found'),
        ];
  






    // $num_names = $form_state->get('num_names');
    // // We have to ensure that there is at least one name field.
    // if ($num_names === NULL) {
    //   $name_field = $form_state->set('num_names', 1);
    //   $num_names = 1;
    // }
    
    // $form['#tree'] = TRUE;
    // $form['names_fieldset'] = [
    //   '#type' => 'fieldset',
    //   // '#title' => $this->t('Home News Ordering'),
    //   '#prefix' => '<div id="names-fieldset-wrapper">',
    //   '#suffix' => '</div>',
    // ];

    
    // for ($x = 0; $x < $num_names; $x++) {


    //     $title = $url = $w = '';
    //   if (array_key_exists($x,$data)) {
    //     $title = $data[$x]['link_title'];
    //     $url = $data[$x]['link_url'];
    //     $w = $data[$x]['weight'] ;
    //     $tooltip = $data[$x]['tooltip'] ;
    //   }
    //   else {
    //     if(array_key_exists($x-1,$data)) {
    //       $w = $data[$x-1]['weight'] + 1;
    //     }
  




    $form['link_container']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_scfp_newsletter'); $i++) {
      // $scfp_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      // $remove_path = '/admin/content/manage/get-help-block/links-remove/'.$scfp_id;
      
     
      // $remove_link = 'Remove';
      // $links = '';
      // $links = '<div class="scfp-get-help-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
      // $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
      // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';
     














    
      $form['link_container'][$i] = [
       'scfp_newsletter_title' => [
          '#title' => t('Newsletter Name'),
          '#type' => 'textfield',
          // '#default_value' => $title,
          '#default_value' => !empty($data[$i]['link_title']) ? $data[$i]['link_title'] : '',
          '#prefix' => '<span>',
          '#suffix' => '</span>',
      ],
        'scfp_newsletter_email' => [
          '#title' => t('Newsletter Emails'),
          '#type' => 'textfield',
          // '#default_value' =>  $url,
          '#default_value' => !empty($data[$i]['link_url']) ? $data[$i]['link_url'] : '',
          '#prefix' => '<span>',
          '#suffix' => '</span>',
        ],
        'scfp_newsletter_tooltip' => [
          '#type' => 'textarea',
          '#title' => t('Newsletter Tooltip'),
          '#default_value' => !empty($data[$i]['tooltip']) ? $data[$i]['tooltip'] : '',
          // '#default_value' => $tooltip,
          //'#delta' => 50,
        ],
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          // '#default_value' => $w,
          '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : 0,
          '#delta' => 50,
          '#title_display' => 'invisible'
        ],
  ];
      // $weight++;
// }
  }
  
 



      $form['actions']['add_name'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add another Newsletter'),
        '#submit' => ['::scfp_newsletter_ordering_form_add_more'],
       
      ];
    


    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
    //   '#submit' => array('_scfp_newsletter_ordering_form_submit'),
      '#attributes'=>array('class'=> array('external-submit-button')),
    );






  
    return $form;


  }





public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['names_fieldset'];
    }

    
    public function scfp_newsletter_ordering_form_add_more(array &$form, FormStateInterface $form_state) {
        // $name_field = $form_state->get('num_names');
        // $add_button = $name_field + 1;
        // $form_state->set('num_names', $add_button);
        
        // $form_state->setRebuild();


        $tempstore = \Drupal::service('tempstore.private')->get('scfp_newsletter');
        if (empty($tempstore->get('num_rows_scfp_newsletter')) || $tempstore->get('num_rows_scfp_newsletter') == null) {
          $tempstore->set('num_rows_scfp_newsletter', 1);
        }else{
          $tempstore->set('num_rows_scfp_newsletter', $tempstore->get('num_rows_scfp_newsletter') + 1);
        }
        $form_state->setRebuild();
    

        }
        

  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {

  $value = $form_state->getValues();
  // dd($value);
  foreach ($form_state->getValues() as $key => $value) {
    $this->config('scfp_newsletter.settings')
        ->set($key, $value)->save();

//     // parent::submitForm($form, $form_state);
}




      if(is_array($form_state->getValues(['link_container']))) {
        foreach ($form_state->getValue(['link_container']) as $key => $value) {
          // dd($value);
          if(is_array($form_state->getValues(['link_container']))) {
            if($value['scfp_newsletter_title'] === "" && $value['scfp_newsletter_email'] === "") {
              continue;
            }
    
            \Drupal::database()->merge('scfp_newsletter_ordering')
              ->key(array('id' => $key))
              ->fields(array(
                'newsletter_title' => $value['scfp_newsletter_title'],
                'newsletter_email' => $value['scfp_newsletter_email'],
                'newsletter_tooltip' => $value['scfp_newsletter_tooltip'],
                'weight' => $value['weight'],
    
              ))
              ->execute();
          }
        }
      }


      \Drupal::messenger()->addMessage('Form has been saved successfully');
   
  
}




// function get_newsletter_data(){
//     $data = [];
//     $query = \Drupal::database()->select('scfp_newsletter_ordering', 'r');
//     $query->addField('r', 'id', 'id');
//     $query->addField('r', 'newsletter_title', 'link_title');
//     $query->addField('r', 'newsletter_email', 'link_url');
//     $query->addField('r', 'newsletter_tooltip', 'tooltip');
//     $query->addField('r', 'weight', 'weight');
//     $query->orderBy('r.weight', 'ASC');
//     $result = $query->execute()->fetchAll();
//     foreach ($result as $key => $item) {
//       $data[] = [
//         'id' => is_object($item) ? $item->id : 0,
//         'link_title' => is_object($item) ? $item->link_title : '',
//         'link_url' => is_object($item) ? $item->link_url : 0,
//         'tooltip' => is_object($item) ? $item->tooltip : 0,
//         'weight' => is_object($item) ? $item->weight : 50,
//       ];
//     }
//     return $data;
//   }
  





}









