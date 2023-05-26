<?php
/**
 * @file
 * Contains \Drupal\manageordering\Form\cross_marketing_ordering_form.
 */
namespace Drupal\manageordering\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\HttpFoundation\Request;
class cross_marketing_ordering_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cross_marketing_ordering_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $data = $this-> get_cross_marketing_ordering_data();
    $form['resource_items']['#tree'] = TRUE;
    $weight = -50;
    $attr = ['readonly' => 'readonly'];
    foreach ($data as $i => $item) {
    $form['resource_items'][$i] = [
      'enabled' => [
        '#type' => 'checkbox',
        '#title' => '&nbsp;',
        '#default_value' => isset($item) ? $item['enabled'] : 1,
      ],
      'resource' => [
        '#type' => 'textfield',
        '#title'=>'Title',
        '#default_value' => isset($item) ? $item['title'].' [nid:'.$item['id'].']' : '',
        '#size' => 60,
        '#maxlength' => 255,
        '#required' => FALSE,
        '#attributes' => $attr,
      ],
      'weight' => [
        '#type' => 'weight',
        '#title' => t('Weight'),
        '#default_value' => $weight,
        '#delta' => 50,
        '#title_display' => 'invisible',
      ],
    ];
    $weight++;
  }
  $form['actions']['save'] = array(
     '#type' => 'submit',
     '#value' => t('Save Changes'),
   
   );
return $form;


  }
  
  /**
   * get_cross_marketing_ordering_data
   * @return Array
   */
  function get_cross_marketing_ordering_data() {
    $data = [];
    $query =  \Drupal::database()->select('scf_cross_marketing_ordering', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->addField('r', 'nid', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('n', 'title', 'title');
    $query->addField('r', 'enabled', 'enabled');
    $query->condition('n.status', 1);
    $query->orderBy('r.weight', 'ASC');
    $query->orderBy('n.created', 'ASC');
    $result = $query->execute()->fetchAll();
    foreach ($result as $key => $item) {
      $data[] = ['id' => is_object($item) ? $item->id : 0,
        'title' => is_object($item) ? $item->title : '',
        'enabled' => is_object($item) ? $item->enabled : 0,
        'weight' => is_object($item) ? $item->weight : 50,
      ];
    }
    return $data;
  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    // $val= $form_state->getValues();
    // dd($val);

      foreach ($form_state->getValue(['resource_items']) as $id => $item) {

      $matches = array();
      $result = preg_match('/\[.*\:(\d+)\]$/', $item['resource'], $matches);
      $nid = isset($matches[$result]) ? $matches[$result] : 0;
      if($nid > 0) {
        \Drupal::database()->merge('scf_cross_marketing_ordering')
          ->key(array('nid' => $nid))
          ->fields(array(
            'nid' => $nid,
            'weight' => $item['weight'],
            'enabled' => $item['enabled']
          ))
          ->execute();
      }
    }
    \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');


  }
}
