<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\webcast_ordering_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Render\Markup;

class webcast_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webcast_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state ) {

  }



 
  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {


    
}











// $form['podcast_id'] = [
//   '#type' => 'hidden',
//   '#value' => $podcast_id,
// ];
// $weight = -50;
// $data = [];
// $attr = [];
// $attr_checkbox = FALSE;
// $args = [];
// $args = arg();
// $destination = implode('/',$args);
// $form['resource_items']['#tree'] = TRUE;
// $data = get_podcasts($podcast_id);
// if (empty($form_state['num_rows'])) {
// $form_state['num_rows'] = count($data);
// }
// for ($i = 0; $i < $form_state['num_rows']; $i++) {
// $item = isset($data[$i]) ? $data[$i] : NULL;
// $item_id = isset($item) ? $item['id'] : 0;
// $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];
// $remove_path = 'admin/managing/manage-resource-order/webcasts/remove/'.$item_id;
// $remove_link = '';
// //$remove_link = l('Remove', $remove_path, array('query' => array('destination' => $destination)));
// $links = '';
// $links = '<div class="scf-links">'.$remove_link. '</div>';
// $form['resource_items'][$i] = [
//   'is_selected' => [
//     '#type' => 'checkbox',
//     '#title' => '&nbsp;',
//     '#default_value' => isset($item) ? $item['is_selected'] : 1,
//     '#disabled' => $attr_checkbox,
//   ],
//   'resource' => [
//     '#type' => 'textfield',
//     '#default_value' => isset($item) ? $item['title'].' [nid:'.$item['id'].']' : '',
//     '#size' => 60,
//     '#maxlength' => 255,
//    // '#autocomplete_path' => 'admin/managing/manage-resource-order/webcasts/autocomplete/'.$podcast_id,
//     '#required' => FALSE,
//     '#attributes' => $attr,
//   ],
//   'links' => [
//     '#type' => 'item',
//     '#markup' => $links,
//   ],
//   'weight' => [
//     '#type' => 'weight',
//     '#title' => t('Weight'),
//     '#default_value' => $weight,
//     '#delta' => 50,
//     '#title_display' => 'invisible',
//   ],
// ];
// $weight++;
// }

// $form['actions']['save'] = array(
//  '#type' => 'submit',
//  '#value' => t('Save Changes'),
//  '#submit' => array('_webcast_ordering_form_save'),
// );
// $form['actions']['download_all'] = array(
//   '#type' => 'submit',
//   '#value' => t('Download CSV'),
//   '#submit' => array('_webcast_ordering_form_download_all'),
// );
// return $form;






}