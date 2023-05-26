<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_get_help_form.
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
 

class scfp_get_help_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_get_help.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_get_help_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['scfp_get_help.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_get_help.settings');
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_get_help');
    




    // drupal_set_title('Get Help Block');
    $weight = -50;
    // $data = [];
    // $form['link_container']['#tree'] = TRUE;
    // $data = $this->get_help_links();
// dd($data);
  
    $form['scfp_get_help_title'] = array(
      '#type' => 'textfield',
      '#title' => t('SCFP Get Help Block Title'),
      '#default_value' => $config->get ('scfp_get_help_title', 'get_help Title'),
      '#size' => 100,
      '#maxlength' => 255,
      '#description' => t('This title will display for Get help block in Navigate our practice journey'),
    );

    // $form['text']=array(
    //     '#type'=>'markup',
    //     '#markup'=>'SCFP Submit Button Label and UR'

    // );

    $form['scfp_get_help_submit_title'] = array(
        '#type' => 'textfield',
        '#title' => t(' Title'),

        '#default_value' => $config->get ('scfp_get_help_submit_title'),
        '#size' => 100,
        '#maxlength' => 255, 
        '#description'=>t('The link title is limited to characters maximum.')
     
      );

      $form['scfp_get_help_submit_url'] = array(
        '#type' => 'textfield',
        '#title' => t(' URL'),
        '#default_value' => $config->get ('scfp_get_help_submit_url'),
        '#size' => 100,
        '#maxlength' => 255,
     
      );




      $query = \Drupal::database()->select('scfp_get_help_ordering', 'r');
        $query->addField('r', 'id', 'id');
        $query->addField('r', 'link_title', 'link_title');
        $query->addField('r', 'link_url', 'link_url');
        $query->addField('r', 'weight', 'weight');
        $query->orderBy('r.weight', 'ASC');
        $result = $query->execute()->fetchAll();
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
        if (empty($tempstore->get('num_rows_scfp_get_help')) || $tempstore->get('num_rows_scfp_get_help') == null) {
          $tempstore->set('num_rows_scfp_get_help', $count_data);
    
        }


         $header = array(t('SCFP Get help Links'), '', t('Weight'));
       
        
          $form['table'] = [
              '#type' => 'table',
              '#header' => $header,
              // 'caption' => $help_text,
              // '#rows' => $data,
              // '#empty' => $this->t('No data found'),
              ];

              
              $form['link_container']['#tree'] = TRUE;
              for ($i = 0; $i < $tempstore->get('num_rows_scfp_get_help'); $i++) {
                // $scfp_id = isset($data[$i]) ? $data[$i]['id'] : 0;
                // $remove_path = '/admin/content/manage/get-help-block/links-remove/'.$scfp_id;
                
               
                // $remove_link = 'Remove';
                // $links = '';
                // $links = '<div class="scfp-get-help-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
                // $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
                // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';
               



      // $form['names_fieldset']['link_container'][$x] = [
        $form['link_container'][$i] = [
       'scfp_get_help_link_title' => [
          '#title' => t('Title'),
          '#type' => 'textfield',
          // '#default_value' => $title,
          '#default_value' => !empty($data[$i]['link_title']) ? $data[$i]['link_title'] : '',
          '#prefix' => '<span>',
          '#suffix' => '</span>',
      ],
        'scfp_get_help_link_url' => [
          '#title' => t('Email'),
          '#type' => 'textfield',
          // '#default_value' =>  $url,
          '#default_value' => !empty($data[$i]['link_url']) ? $data[$i]['link_url'] : '',
          '#prefix' => '<span>',
          '#suffix' => '</span>',
        ],
     
        'weight' => [
          '#type' => 'weight',
          // '#type' => 'hidden',
          '#title' => t('Weight'),
          // '#default_value' => $w,
          '#default_value' => !empty($data[$i]['weight']) ? $data[$i]['weight'] : 0,
          '#delta' => 50,
          '#title_display' => 'invisible'
        ],
        // 'links' => [
        //   '#type' => 'item',
        //   '#markup' => $links,
        // ],
  ];
      // $weight++;
  }


  
 

  $form['names_fieldset']['actions']['add_name'] = [
    '#type' => 'submit',
    '#value' => $this->t('Add another link'),
    '#submit' => ['::_scfp_get_help_ordering_form_add_more'],
   
  ];




    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
    //   '#submit' => array('_scfp_get_help_ordering_form_submit'),
      // '#attributes'=>array('class'=> array('external-submit-button')),
    );
  
    return $form;


  }






    
    public function _scfp_get_help_ordering_form_add_more(array &$form, FormStateInterface $form_state) {
     
        $tempstore = \Drupal::service('tempstore.private')->get('scfp_get_help');
        if (empty($tempstore->get('num_rows_scfp_get_help')) || $tempstore->get('num_rows_scfp_get_help') == null) {
          $tempstore->set('num_rows_scfp_get_help', 1);
        }else{
          $tempstore->set('num_rows_scfp_get_help', $tempstore->get('num_rows_scfp_get_help') + 1);
        }
        $form_state->setRebuild();
    


     

        }
        

        

  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
  $val = $form_state->getValues();

//   db_truncate('scfp_get_help_ordering')->execute();
  foreach ($form_state->getValues() as $key => $value) {
    $this->config('scfp_get_help.settings')
        ->set($key, $value)->save();

//     // parent::submitForm($form, $form_state);
}
   
  
        foreach ($form_state->getValue(['link_container']) as $key => $value) {
      
        
        if(is_array ($form_state->getValue(['link_container'])) ){
          if($value['scfp_get_help_link_title'] === "" && $value['scfp_get_help_link_url'] === "") {
            continue;
          }
  
  // $tempstore = \Drupal::service('tempstore.private')->get('scfp_get_help');
  //   if ($items != null) {
  //     $tempstore->set('num_rows_scfp_get_help', count($items));
  //   }

    \Drupal::database()->merge('scfp_get_help_ordering')
    // ->key(array('id' => $value['link_container']))
            ->key(array('id' => $key))
            ->fields(array(
              'link_title' => $value['scfp_get_help_link_title'],
              'link_url' => $value['scfp_get_help_link_url'],
              'weight' => $value['weight'],
  
            ))
            ->execute();
        
            // }
        }
      // }
    
    }
    \Drupal::messenger()->addMessage('Form has been saved successfully');
     
    
    // }












  
}




}





  












