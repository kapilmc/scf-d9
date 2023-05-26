<?php
/**
 * @file
 * Contains \Drupal\scf_mcsi\mcsi_get_involved_form.
 */
namespace Drupal\scf_mcsi\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;


class mcsi_get_involved_form extends ConfigFormBase {
 /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'mcsi_get_involved.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mcsi_get_involved_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    // // drupal_set_title('How to get involved');
    // $cat_markup = '<ul>';
    // $cat_markup .= '<li>' . l('How to get involved', 'admin/mcsi/how-to-get-involved') . '</li>';
    // $cat_markup .= '<li>' . l('FAQ', 'admin/mcsi/how-to-get-involved/faq') . '</li>';
    // $cat_markup .= '</ul>';
    // $form['resource_category'] = [
    //   '#type' => 'item',
    //   '#markup' => $cat_markup,
    // ];
    $overview = $config->get('mcsi_how_to_get_involved', '');
    $form['mcsi_how_to_get_involved_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
    //   '#default_value' => alt_vars_get('mcsi_how_to_get_involved_title'),
    // '#default_value' =>'How to get involved' ,
    '#default_value' => $config->get('mcsi_how_to_get_involved_title'),
    );

    
    $form['mcsi_how_to_get_involved'] = array(
      '#type' => 'text_format',
      '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
      // '#format' => 'filtered_html',  
      '#title' => t('Description'),
      '#default_value' => isset($overview['value']) ? $overview['value'] : '',
    
      '#rows' => 6,
    

    );


   
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save Changes'),
    //   '#submit' => array([$this,'_mcsi_get_involved_submit']),
    );
    return $form;
  }



  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {
    //      $val= $form_state->getvalues();
  //   dd($val);
  $this->config(static::SETTINGS)
  ->set( 'mcsi_how_to_get_involved_title',$form_state->getValue('mcsi_how_to_get_involved_title'))
  ->set( 'mcsi_how_to_get_involved',$form_state->getValue('mcsi_how_to_get_involved'))
  ->save();
  \Drupal::messenger()->addMessage('Form has been saved successfully');




  
}
}