<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_teamup_calendar_config_form.
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
 

class scfp_teamup_calendar_config_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_teamup_calendar.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_teamup_calendar_config_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'scfp_teamup_calendar.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_teamup_calendar.settings');
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_teamup');

    


   

    $form['scfp_teamup_calendar_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Teamup Calendar Block Title'),
      '#default_value' => $config->get('scfp_teamup_calendar_title', 'Explore the Strategy & Corporate Finance Events'),
      '#size' => 100,
      '#maxlength' => 255,
    );
    $form['scfp_teamup_calendar_email'] = array(
      '#type' => 'textfield',
      '#title' => t('Teamup Email'),
      '#default_value' => $config->get('scfp_teamup_calendar_email', 'strategy_platform@mckinsey.com'),
      '#size' => 100,
      '#maxlength' => 255,
    );
    $form['scfp_teamup_calendar_base_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Base Teamup URL for calendar and individual events'),
      '#default_value' => $config->get('scfp_teamup_calendar_base_url', 'https://teamup.com'),
      '#size' => 100,
      '#maxlength' => 255,
    );
    $form['container'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'id' => array('teamup-header-container'),
      ),
    );
    $form['container']['scfp_teamup_api_header'] = [
      'scfp_teamup_calendar_header_key' => [
        '#title' => t('API Header key'),
        '#type' => 'textfield',
        '#default_value' => $config->get('scfp_teamup_calendar_header_key', 'Teamup-token'),
        '#prefix' => '<span>',
        '#suffix' => '</span>',
      ],
      'scfp_teamup_calendar_header_value' => [
        '#title' => t('API Header Token value'),
        '#type' => 'textfield',
        
        '#default_value' => $config->get('scfp_teamup_calendar_header_value', ''),
        '#prefix' => '<span>',
        '#suffix' => '</span>',
      ],
    ];
    $form['scfp_teamup_calendar_api_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Teamup Calendar API URL'),
      '#default_value' => $config->get('scfp_teamup_calendar_api_url', 'https://api.teamup.com'),
      '#size' => 100,
      '#maxlength' => 255,
    );
    $form['scfp_teamup_calendar_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Teamup Calendar Key'),
      '#default_value' => $config->get('scfp_teamup_calendar_key', ''),
      '#size' => 100,
      '#maxlength' => 255,
    );
    $form['scfp_teamup_calendar_password'] = array(
      '#type' => 'textfield',
      '#title' => t('Teamup Calendar Password'),
      '#default_value' =>$config->get('scfp_teamup_calendar_password', ''),
      '#size' => 100,
      '#maxlength' => 255,
    );
  
    $options = array();
    $options[1] = 'Americas - External Events';
    $options[2] = 'Americas - Internal Events';
    $options[3] = 'Asia - External Events';

    // $options = $this->getEventCalanderOptions();
// dd($options);
    $form['scfp_teamup_subcalendar_ids'] = array(
      '#type' => 'checkboxes',

      '#title' => t('Choose Calendars'),
     '#options'=> $options,
      // '#options' => $this->getEventCalanderOptions(),
      '#default_value' => $config->get('scfp_teamup_subcalendar_ids', ''),
      '#description' => 'Select to display events from selected calnder only. If no any calender selected then it will display all events.',
    );
  


    
    $query = \Drupal::database()->select('scfp_teamup_ordering', 'r');
    $query->addField('r', 'id', 'id');
    $query->addField('r', 'link_title', 'link_title');
    $query->addField('r', 'link_url', 'link_url');
    $query->addField('r', 'weight', 'weight');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();




    // foreach ($result as $key => $item) {
    //   $data[] = [
    //     // 'id' => is_object($item) ? $item->id : 0,
    //     'link_title' => is_object($item) ? $item->link_title : '',
    //     'link_url' => is_object($item) ? $item->link_url : 0,
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
        $data[$ke]['weight'] = $val->weight;

       
      }

      $count_data = count($data);
      // var_dump($count_data);
      // dd($count_data);
    }
    if (empty($tempstore->get('num_rows_scfp_teamup')) || $tempstore->get('num_rows_scfp_teamup') == null) {
      $tempstore->set('num_rows_scfp_teamup', $count_data);

    }



    $help_text = 'Rearrange/Enter links for scfp_teamup_calendar Block';
    $header = array(t('SCFP TeamUp calendar Links'), '', t('Weight'));
        
    $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        // 'caption' => $help_text,
        // '#rows' => $data,
        // '#empty' => $this->t('No data found'),
        ];
  



    $form['link_container']['#tree'] = TRUE;
    for ($i = 0; $i < $tempstore->get('num_rows_scfp_teamup'); $i++) {
      // $scfp_id = isset($data[$i]) ? $data[$i]['id'] : 0;
      // $remove_path = '/admin/content/manage/get-help-block/links-remove/'.$scfp_id;
      
     
      // $remove_link = 'Remove';
      // $links = '';
      // $links = '<div class="scfp-get-help-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
      // $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
      // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';
     

  
  



      $form['link_container'][$i] = [
        'scfp_teamup_calendar_link_title' => [
          '#title' => t('Label'),
          '#type' => 'textfield',
          // '#default_value' => $title,
          '#default_value' => !empty($data[$i]['link_title']) ? $data[$i]['link_title'] : '',
          '#prefix' => '<span>',
          '#suffix' => '</span>',
        ],
        'scfp_teamup_calendar_link_url' => [
          '#title' => t('Links'),
          '#type' => 'textfield',
          // '#default_value' => $url,
          '#default_value' => !empty($data[$i]['link_url']) ? $data[$i]['link_url'] : '',
          '#prefix' => '<span>',
          '#suffix' => '</span>',
        ],
        'weight' => [
          '#type' => 'weight',
          '#title' => t('Weight'),
          // '#default_value' => $w,
          '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : '',
          '#delta' => 50,
          '#title_display' => 'invisible',
        ],
  
      ];
      // $weight++;
    }
    // $form['actions']['add_more'] = array(
    //   '#type' => 'submit',
    //   '#value' => t('Add another link'),
    //   '#submit' => array('_scfp_teamup_ordering_form_add_more'),
    // );



    // $form['actions']['add_more'] = [
    //     '#type' => 'submit',
    //     '#value' => $this->t('Add another link'),
    //     '#submit' => ['::scfp_teamup_ordering_form_add_more'],
    //     '#ajax' => [
    //       'callback' => '::addmoreCallback',
    //       'wrapper' => 'names-fieldset-wrapper',
    //     ],
    //   ];
    
    

      $form['actions']['add_name'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add another link'),
        '#submit' => ['::scfp_teamup_ordering_form_add_more'],
       
      ];
    






    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
    //   '#submit' => array('_scfp_teamup_ordering_form_submit'),
      // '#attributes' => array('class' => array('external-submit-button')),
    );
  
    return $form;


  }
    public function scfp_teamup_ordering_form_add_more (array &$form, FormStateInterface $form_state) {
        // $name_field = $form_state->get('num_names');
        // $add_button = $name_field + 1;
        // $form_state->set('num_names', $add_button);
        
        // $form_state->setRebuild();


        $tempstore = \Drupal::service('tempstore.private')->get('scfp_teamup');
        if (empty($tempstore->get('num_rows_scfp_teamup')) || $tempstore->get('num_rows_scfp_teamup') == null) {
          $tempstore->set('num_rows_scfp_teamup', 1);
        }else{
          $tempstore->set('num_rows_scfp_teamup', $tempstore->get('num_rows_scfp_teamup') + 1);
        }
        $form_state->setRebuild();
    




        }




        function getEventCalanderOptions() {
          $config = $this->config('scfp_teamup_calendar.settings');


          // dd('sd,mcnksd');
          $options = [];
          $api_path = $config->get('scfp_teamup_calendar_api_url', 'https://api.teamup.com') . '/' . $config->get('scfp_teamup_calendar_key', '') . '/subcalendars';
          // dd($api_path);
          $headers = [
            'teamup-password' => $config->get('scfp_teamup_calendar_password', ''),
            'teamup-token' => $config->get('scfp_teamup_calendar_header_value', ''),
          ];
          $result = drupal_http_request($api_path, ['headers' => $headers]);
          if ($result->code == 200) {
            $data = $result->data;
            $dd = json_decode($data);
            $options = [];
            foreach ($dd->subcalendars as $item) {
              $options[$item->id] = $item->name;
            }
          }
          else {
            return $options;
          }
          return $options;
        }
        
        






  public function validateForm(array &$form, FormStateInterface $form_state) {

    // if(strlen($form_state->getValue('scfp_teamup_calendar_link_url')) ) {
    //   $form_state->setErrorByName('scfp_teamup_calendar_link_url', $this->t('Please enter a valid links'));
    // }
    // if(strlen($form_state->getValue('student_phone')) < 10) {
    //   $form_state->setErrorByName('student_phone', $this->t('Please enter a valid Contact Number'));
    // }





}

public function submitForm(array &$form, FormStateInterface $form_state) {

// $val = $form_state->getValues();
// dd($val);

    // $this->config('scfp_teamup_calendar.settings')

    foreach ($form_state->getValues() as $key => $value) {
      $this->config('scfp_teamup_calendar.settings')
          ->set($key, $value)->save();
  
  //     // parent::submitForm($form, $form_state);
  }
  


    // ->set('scfp_teamup_calendar_title' , $form_state->getValue('scfp_teamup_calendar_title'))
    // ->set('scfp_teamup_calendar_header_key',$form_state->getValue('scfp_teamup_calendar_header_key'))
    // ->set('scfp_teamup_calendar_header_value',$form_state->getValue('scfp_teamup_calendar_header_value'))

    // ->set('scfp_teamup_calendar_email',$form_state->getValue('scfp_teamup_calendar_email'))
    // ->set('scfp_teamup_calendar_base_url',$form_state->getValue('scfp_teamup_calendar_base_url'))
    // ->set('scfp_teamup_calendar_api_url',$form_state->getValue('scfp_teamup_calendar_api_url'))

    // ->set('scfp_teamup_calendar_key',$form_state->getValue('scfp_teamup_calendar_key'))
    // ->set('scfp_teamup_calendar_password',$form_state->getValue('scfp_teamup_calendar_password'))
    // ->set(' scfp_teamup_subcalendar_ids',$form_state->getValue('  scfp_teamup_subcalendar_ids'))


  
    // // ->save();

      foreach ($form_state->getValue(['link_container']) as $key => $value) {
      
        if (is_array($form_state->getValue(['link_container']))) {

     
        if ($value['scfp_teamup_calendar_link_title'] === "" && $value['scfp_teamup_calendar_link_url'] === "") {
          continue;
        }
  
             \Drupal::database()->merge('scfp_teamup_ordering')
          ->key(array('id' => $key))
          ->fields(array(
            'link_title' => $value['scfp_teamup_calendar_link_title'],
            'link_url' => $value['scfp_teamup_calendar_link_url'],
            'weight' => $value['weight'],
  
          ))
          ->execute();
      }
    

    }


    \Drupal::messenger()->addMessage('Form has been saved successfully');


}






// function get_teamup_links() {
//     $data = [];
//     $query = \Drupal::database()->select('scfp_teamup_ordering', 'r');
//     $query->addField('r', 'id', 'id');
//     $query->addField('r', 'link_title', 'link_title');
//     $query->addField('r', 'link_url', 'link_url');
//     $query->addField('r', 'weight', 'weight');
//     $query->orderBy('r.weight', 'ASC');
//     $result = $query->execute()->fetchAll();
//     foreach ($result as $key => $item) {
//       $data[] = [
//         'id' => is_object($item) ? $item->id : 0,
//         'link_title' => is_object($item) ? $item->link_title : '',
//         'link_url' => is_object($item) ? $item->link_url : 0,
//         'weight' => is_object($item) ? $item->weight : 50,
//       ];
//     }
//     return $data;
//   }








}





