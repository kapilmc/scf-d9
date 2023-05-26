<?php
/**
 * @file
 * Contains \Drupal\scf_mcsi\Form\mcsi_faq_form.
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

 

class mcsi_faq_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mcsi_faq_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {

  
  


    // $form['items']['#tree'] = TRUE;
    // $form['mcsi_faq_title'] = array(
    //   '#type' => 'textfield',
    //   '#title' => t('Title'),
    //   '#default_value' => 'FAQ',
    //   '#weight' => -1,
    // );
  




    // $data = [];
    // $know_id = '';
    $query = \Drupal::database()->select('scf_mcsi_faq_ordering', 'r');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->addField('r', 'nid', 'id');
    $query->addField('r', 'weight', 'weight');
    $query->addField('r', 'status', 'status');
    $query->addField('n', 'title', 'title');
    $query->orderBy('r.weight', 'ASC');
    $result = $query->execute()->fetchAll();
    foreach ($result as $key => $item) {

      $rows[] = [

        // $url_edit =Url::fromRoute('scf_mcsi.mcsi_faq_form', ['nid' => $data->nid], []);

        // 'id' => is_object($item) ? $item->id : 0,

        'weight' => is_object($item) ? $item->weight : 50,
        'title' => is_object($item) ? $item->title : '',
        'status' => $item->status==1 ? 'Yes' : 'No',
        // 'weight' => is_object($item) ? $item->weight : 50,
        // 'action' =>  $linkEdit,
        'action' => Markup::create('<a href="/node/'.$item->id.'/edit?destination=admin/mcsi/how-to-get-involved/faq">edit</a>'),
        

      ];
    }
// dd($data);





$header = [
  // 'id'=> t ('nid'),
  'weight'=>t('Weight'),
'title' => t('FAQ'),
'status' => t('Published'),

'action' => $this->t('Action')


];







// $header = array(t('FAQ'), t('Published'), t('Actions'), t('Weight'));
        
          // render table
          $form['names_fieldset']['table'] = [
              '#type' => 'table',
              '#header' =>  $header,
              '#rows' => $rows,
    
              '#empty' => $this->t('No data found'),
          ];






          $form['items']['#tree'] = TRUE;
          $form['mcsi_faq_title'] = array(
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#default_value' => 'FAQ',
            '#weight' => -1,
          );


       
      // $form['items'][$i] = [
      //       'faq' => [
      //         '#type' => 'textfield',
      //         // '#default_value' => isset($item) ? $item['title'] : '',
      //         '#size' => 60,
      //         '#maxlength' => 255,
      //         '#required' => FALSE,
      //         '#attributes' => $attr,
      //       ],
      //       'status' => [
      //         '#type' => 'item',
      //         // '#markup' => isset($item['status']) && $item['status'] == 1 ? 'Yes' : 'No',
            
      //       ],
      //       'links' => [
      //         '#type' => 'item',
      //         '#markup' => $links,
      //       ],
      //       'id' => [
      //         '#type' => 'hidden',
      //         // '#default_value' => $item['id'],
      //       ],
      //       'weight' => [
      //         '#type' => 'weight',
      //         '#title' => t('Weight'),
      //         '#default_value' => $weight,
      //         '#delta' => 50,
      //         '#title_display' => 'invisible',
      //       ],
      //     ];
      //     $weight++;
      
       
  
      


        $form['actions'] = array('#type' => 'actions');
        $form['actions']['save'] = array(
          '#type' => 'submit',
          '#value' => t('Save Changes'),
        //   '#submit' => array('_mcsi_faq_form_save'),
        );




        $form['actions']['add_faq'] = array(
          '#type' => 'submit',
          '#value' => t('Add FAQ'),
          '#submit' => array([$this,'_add_faq']),
        );
 


        

 


    return $form;

  }



    public function _add_faq(array &$form, FormStateInterface $form_state) {
        // drupal_goto('node/add/faq', array('query' => array('destination' => 'admin/mcsi/how-to-get-involved/faq')));

        // $redirect =new RedirectResponse('node/add/faq', array('query' => array('destination' => 'admin/mcsi/how-to-get-involved/faq')));
        $redirect =new RedirectResponse('/node/add/faq?destination=admin/mcsi/how-to-get-involved/faq');
    
        $redirect->send();

        }
  public function validateForm(array &$form, FormStateInterface $form_state) {

}


public function submitForm(array &$form, FormStateInterface $form_state) {
    // $val= $form_state->getvalues();
    // dd(  $val);

   
      
    // // foreach ($form_state->getvalues()['items'] as $id => $item) {
    // //     dd($item)
    // //     $nid = isset($item['id']) ? $item['id'] : 0;
    // //     if ($nid > 0) {
    // //         \Drupal::database()->merge('scf_mcsi_faq_ordering')
    // //         ->key(array('nid' => $nid))
    // //         ->fields(array(
    // //           'nid' => $nid,
    // //           'weight' => $item['weight'],
    // //         ))
    // //         ->execute();
    // //     }
    // //   }

      \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');

}






}