<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_get_involved_form.
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
 

class scfp_get_involved_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_get_involved.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_get_involved';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['scfp_get_involved.settings'];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_get_involved.settings');

    

    // // $journey_tid = scf_get_tid_from_uuid('7fe01294-357f-4f72-86c5-80293fef361e');
    // $query = \Drupal::database()->select('node', 'n');
    // $query->join('field_data_field_user_journey', 'j','j.entity_id = n.nid');
    // $query->fields('n', ['nid', 'title'])
    //   ->condition('n.type', 'resource', '=')
    //   ->condition('n.status', 1, '=')
    //   // ->condition('n.title', '%' . db_like($string) . '%', 'LIKE')
    //   ->condition('j.field_user_journey_tid', $journey_tid, '=')
    //   ->range(0, 20);
    // $items = $query->execute();
    // dd($items);
    // foreach ($items as $item) {
    //     $matches[$item->title.' [nid:'.$item->nid.']'] = check_plain($item->title).' [nid:'.$item->nid.']';
    // }







    // drupal_set_title('Get involved configuration');
    $form['block1'] = array(
      '#type' => 'fieldset',
      '#title' => t('Block first'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      );
    $form['get_involved_block1_id'] = array(
        '#type' => 'hidden',
        '#title' => t('Id 1'),
        '#default_value' => 1,
        );
    $form['get_involved_block2_id'] = array(
          '#type' => 'hidden',
          '#title' => t('Id 2'),
          '#default_value' => 2,
        );
    $form['get_involved_block3_id'] = array(
          '#type' => 'hidden',
          '#title' => t('Id 3'),
          '#default_value' => 3,
          );
    $form['get_involved_block4_id'] = array(
          '#type' => 'hidden',
          '#title' => t('Id 4'),
          '#default_value' => 4,
          );
  
    $form['block1']['get_involved_block1_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' =>  $config->get('get_involved_block1_title', ''),
      '#rows' => 3,
      );
    $form['block1']['get_involved_block1_summary'] = array(
        '#type' => 'textarea',
        '#title' => t('Overview'),
        '#default_value' => $config->get('get_involved_block1_summary', ''),
        '#rows' => 3,
        );
    $form['block1']['get_involved_block1_nid'] = array(
         '#type' => 'textfield',
         '#title' => t('Content'),
         '#default_value' =>  $config->get('get_involved_block1_nid', ''),
        //  '#autocomplete_path' => 'admin/get-involved/autocomplete',
        '#autocomplete_route_name' => 'scfp_miscellaneous_blocks.scfp_get_involved_autocomplete',
        );
     // $desc1 = variable_get('get_involved_block1_desc', '');
     // $form['block1']['get_involved_block1_desc'] = array(
     //  '#type' => 'text_format',
     //  '#title' => t('Description'),
     //  '#default_value' => $desc1['value'],
     //  '#rows' => 6,
     // );
  
    $form['block2'] = array(
      '#type' => 'fieldset',
      '#title' => t('Block second'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      );
    $form['block2']['get_involved_block2_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('get_involved_block2_title', ''),
      '#rows' => 3,
      );
    $form['block2']['get_involved_block2_summary'] = array(
      '#type' => 'textarea',
      '#title' => t('Overview'),
      '#default_value' => $config->get('get_involved_block2_summary', ''),
      '#rows' => 3,
      );
    $form['block2']['get_involved_block2_nid'] = array(
        '#type' => 'textfield',
        '#title' => t('Content'),
        '#default_value' => $config->get('get_involved_block2_nid', ''),
        // '#autocomplete_path' => 'admin/get-involved/autocomplete',
        '#autocomplete_route_name' => 'scfp_miscellaneous_blocks.scfp_get_involved_autocomplete',
        );
    // $desc2 = variable_get('get_involved_block2_desc', '');
    // $form['block2']['get_involved_block2_desc'] = array(
    //   '#type' => 'text_format',
    //   '#title' => t('Description'),
    //   '#default_value' => $desc2['value'],
    //   '#rows' => 6,
    // );
  
    $form['block3'] = array(
      '#type' => 'fieldset',
      '#title' => t('Block third'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      );
    $form['block3']['get_involved_block3_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('get_involved_block3_title', ''),
      '#rows' => 3,
      );
  
    $block3 = $config->get('get_involved_block3_summary', '');
    $form['block3']['get_involved_block3_summary'] = array(
      '#type' => 'text_format',
      '#title' => t('Overview'),
      '#default_value' => isset($block3['value']) ? $block3['value'] : '',
      '#format' => isset($block3['format']) ? $block3['format'] : 'filtered_html',
      '#rows' => 6,
    );
    $form['block3']['get_involved_block3_url'] = array(
      '#type' => 'textfield',
      '#title' => t('URL'),
    //   '#default_value' => variable_get('get_involved_block3_url', ''),
      );
  
    $form['block4'] = array(
      '#type' => 'fieldset',
      '#title' => t('Block fourth'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      );
    $form['block4']['get_involved_block4_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $config->get('get_involved_block4_title', ''),
      '#rows' => 3,
      );
    $form['block4']['get_involved_block4_summary'] = array(
      '#type' => 'textarea',
      '#title' => t('Overview'),
      '#default_value' => $config->get('get_involved_block4_summary', ''),
      '#rows' => 3,
      );
    $form['block4']['get_involved_block4_nid'] = array(
      '#type' => 'textfield',
      '#title' => t('Content'),
      '#default_value' => $config->get('get_involved_block4_nid', ''),
      // '#autocomplete_path' => 'admin/get-involved/autocomplete',
      '#autocomplete_route_name' => 'scfp_miscellaneous_blocks.scfp_get_involved_autocomplete',
      );

    // return system_settings_form($form);




    // $form['actions']['save'] = array(
    //     '#type' => 'submit',
    //     '#value' => t('Save configuration'),
    //   //   '#submit' => array('_scfp_newsletter_ordering_form_submit'),
   
    //   );
  
    // return $form;
    return parent::buildForm($form, $form_state);


  }





  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {



  foreach ($form_state->getValues() as $key => $value) {
    $this->config('scfp_get_involved.settings')
        ->set($key, $value)->save();

    parent::submitForm($form, $form_state);
}



    // $this->config(static::SETTINGS)


    // ->set('get_involved_block1_title',$form_state->getValue('get_involved_block1_title'))
    // ->set('get_involved_block1_summary',$form_state->getValue('get_involved_block1_summary'))
    // ->set('get_involved_block1_nid',$form_state->getValue('get_involved_block1_nid'))

    // ->set('get_involved_block2_title',$form_state->getValue('get_involved_block2_title'))
    // ->set('get_involved_block2_summary',$form_state->getValue('get_involved_block2_summary'))
    // ->set('get_involved_block2_nid',$form_state->getValue('get_involved_block2_nid'))

    // ->set('get_involved_block3_title',$form_state->getValue('get_involved_block3_title'))
    // ->set('get_involved_block3_summary',$form_state->getValue('get_involved_block3_summary'))
    // // ->set('get_involved_block3_nid',$form_state->getValue('get_involved_block3_nid'))
    // ->set('  get_involved_block3_url',$form_state->getValue('  get_involved_block3_url'))

    // ->set('get_involved_block4_title',$form_state->getValue('get_involved_block4_title'))
    // ->set('get_involved_block4_summary',$form_state->getValue('get_involved_block4_summary'))
    // ->set('get_involved_block4_nid',$form_state->getValue('get_involved_block4_nid'))
  
  
    // ->save();









}








}





