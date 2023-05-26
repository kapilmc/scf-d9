<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_external_expert_network_form.
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
 

class scfp_external_expert_network_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_external_expert.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_external_expert_network_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['scfp_external_expert_network.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_external_expert_network.settings');
    $tempstore = \Drupal::service('tempstore.private')->get('scfp_external_expert');
//     drupal_set_title('External Experts Network Block');
//   $weight = -50;
//   $data = [];
//   $form['link_container']['#tree'] = TRUE;
//   $data = get_links();
  
  $form['scfp_external_experts_network_title'] = array(
    '#type' => 'textfield',
    '#title' => t('SCFP External Expert Block Title'),
    '#default_value' =>  $config->get('scfp_external_experts_network_title', 'External Experts Network'),
    '#size' => 100,
    '#maxlength' => 255,
    '#description' => t('Title of S&CF External Experts Network Block which displays under Win Journey'),
  );
  $form['scfp_external_experts_network_description'] = array(
    '#type' => 'textarea',
    '#title' => t('SCFP External Expert Block Description '),
    '#default_value' =>  $config->get('scfp_external_experts_network_description', 'Make the best use of our external experts by leveraging the following assets'),
    '#description' => t('Description of S&CF External Experts Network Block along with the expert pop-up link for the support'),
    '#rows' => 2,
    '#format' => 'filtered_html',
  );
  $form['scfp_external_experts_network_image'] = array(
    '#title' => t('SCFP External Expert Image'),
    '#type' => 'managed_file',
    '#description' => t('The uploaded image will be displayed on this scf external expert block else default image will be used.'),
    '#default_value' =>  $config->get('scfp_external_experts_network_image', ''),
    '#upload_location' => 'public://scfp_lop_image/',
  );


// get data 
  $query = \Drupal::database()->select('scfp_external_network_ordering', 'r');
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
// dd($data);
  $count_data = count($data);
  // var_dump($count_data);
  // dd($count_data);
}
if (empty($tempstore->get('num_rows_scfp_external')) || $tempstore->get('num_rows_scfp_external') == null) {
  $tempstore->set('num_rows_scfp_external', $count_data);

}



$help_text = 'Rearrange/Enter links for External Expert Network Block';

    
$header = array(t('SCFP External Expert Block Links'), '', t('Weight'));


  $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      // 'caption' => $help_text,
      // '#rows' => $data,
      // '#empty' => $this->t('No data found'),
      ];












$form['link_container']['#tree'] = TRUE;
for ($i = 0; $i < $tempstore->get('num_rows_scfp_external'); $i++) {
  // $scfp_id = isset($data[$i]) ? $data[$i]['id'] : 0;
  // $remove_path = '/admin/content/manage/get-help-block/links-remove/'.$scfp_id;
  
 
  // $remove_link = 'Remove';
  // $links = '';
  // $links = '<div class="scfp-get-help-remove"><a href="'.$remove_path.'">'.$remove_link. '</a></div>';
  // $fname = !empty($data[$i]['title']) ? $data[$i]['title'] : '';
  // $lname = !empty($data[$i]['field_last_name_value']) ? $data[$i]['field_last_name_value'] : '';






    $form['link_container'][$i] = [
     'scfp_external_experts_network_link_title' => [
        '#title' => t('Title'),
        '#type' => 'textfield',
        // '#default_value' => $title,
        '#default_value' => !empty($data[$i]['link_title']) ? $data[$i]['link_title'] : '',
        '#prefix' => '<span>',
        '#suffix' => '</span>',
    ],
      'scfp_external_experts_network_link_url' => [
        '#title' => t('URL'),
        '#type' => 'textfield',
        // '#default_value' =>  $url,
        '#default_value' => !empty($data[$i]['link_url']) ? $data[$i]['link_url'] : '',
        '#prefix' => '<span>',
        '#suffix' => '</span>',
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
    
}




  $form['names_fieldset']['actions']['add_name'] = [
    '#type' => 'submit',
    '#value' => $this->t('Add another link'),
    '#submit' => ['::_scfp_ordering_form_add_more'],
   
  ];








  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save Changes'),
    // '#submit' => array('_scfp_ordering_form_submit'),
    '#attributes'=>array('class'=> array('external-submit-button')),
  );

  return $form;
}

/**
 * Add another link callback to add more to the form.
 */





    
    public function _scfp_ordering_form_add_more(array &$form, FormStateInterface $form_state) {
    



        $tempstore = \Drupal::service('tempstore.private')->get('scfp_external_expert');
        if (empty($tempstore->get('num_rows_scfp_external')) || $tempstore->get('num_rows_scfp_external') == null) {
          $tempstore->set('num_rows_scfp_external', 1);
        }else{
          $tempstore->set('num_rows_scfp_external', $tempstore->get('num_rows_scfp_external') + 1);
        }
        $form_state->setRebuild();
    


     

        }
        

  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {

  foreach ($form_state->getValues() as $key => $value) {
    $this->config('scfp_external_expert_network.settings')
        ->set($key, $value)->save();

    // parent::submitForm($form, $form_state);
}


    // ->set( 'scfp_external_experts_network_title',$form_state->getValue('scfp_external_experts_network_title'))
    // ->set( 'scfp_external_experts_network_description',$form_state->getValue('scfp_external_experts_network_description'))
    // ->set( 'scfp_external_experts_network_image',$form_state->getValue('scfp_external_experts_network_image'))
  
    // ->save();

  


    
    foreach ($form_state->getValue(['link_container']) as $key => $value) {
      // dd($value);

        // dd($form_state);
    // foreach ($form_state['values']['link_container'] as $key => $value) {
      $matches = array();
      $result = preg_match('/\[.*\:(\d+)\]$/', $value['scfp_external_experts_network_link_title'], $matches);
      $nid = isset($matches[$result]) ? $matches[$result] : 0;

      // if(is_array($form_state['values']['names_fieldset','link_container'])) {

        if(is_array($form_state->getValue(['link_container'])) ){

        if($value['scfp_external_experts_network_link_title'] === "" && $value['scfp_external_experts_network_link_url'] === "") {
          continue;
        }
  
     \Drupal::database()->merge('scfp_external_network_ordering')
          ->key(array('id' => $key))
          ->fields(array(
            'link_title' => $value['scfp_external_experts_network_link_title'],
            'link_url' => $value['scfp_external_experts_network_link_url'],
            'weight' => $value['weight'],
  
          ))
          ->execute();
      }
    }
          \Drupal::messenger()->addMessage('Form has been saved successfully');


          


  
}




   



}









