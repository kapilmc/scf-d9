<?php

namespace Drupal\scf_logs\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;


/**
 * Provides the form for adding countries.
 */
class scf_logs_form extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scf_logs_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {



    $types = node_type_get_names();
    $form['type'] = [
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => ['all' => '-Any-'] + $types,
      '#default_value' => $type,
      '#prefix' => '<div style="display:block"><div style="display:inline-block; margin:5px;">',
      '#suffix' => '</div>',
  
    ];
  
    $statuses = [1 => 'Published', 0 => 'Unpublihsed', 2 => 'Deleted'];
    $form['status'] = [
      '#type' => 'select',
      '#title' => t('Status'),
      '#options' => ['all' => '-Any-'] + $statuses,
      '#default_value' => $status,
      '#prefix' => '<div style="display:inline-block; margin:5px;">',
      '#suffix' => '</div>',
    ];
  
    $form['actions']['apply'] = array(
      '#type' => 'submit',
      '#value' => t('Apply'),
      '#submit'=>array([$this, '_scf_logs_form_redirect']),
    );
  
    $form['actions']['reset'] = array(
      '#type' => 'submit',
      '#value' => t('Reset'),
    //   '#submit' => array('_scf_logs_form_reset'),
      '#submit'=>array([$this, '_scf_logs_form_reset']),
      '#suffix' => '</div>',
    );
  
    // $data = scf_logs_data($type, $status);
    // $data = scf_logs_data();
    $form['content'] = [
      '#type' => 'item',
    //   '#markup' => drupal_render($data),
    //   '#markup' =>\Drupal::service('renderer')->render($data);

    ];

    $header = array(
      array('data' => t('Node Id'), 'field' => 'nid'),
      array('data' => t('Title'), 'field' => 'title'),
      array('data' => t('Type'), 'field' => 'type'),
      array('data' => t('Updated By'), 'field' => 'uid'),
      array('data' => t('Status'), 'field' => 'status'),
      array('data' => t('Updated'), 'field' => 'changed', 'sort' => 'desc'),
      array('data' => t('Comment'), 'field' => 'comment'),
  );





  // get data from database
  $statuses = [1 => 'Published', 0 => 'Unpublihsed', 2 => 'Deleted'];
  $query = \Drupal::database()->select('scf_logs', 'm');
  $query->fields('m', ['nid', 
  'title',
   'type',
   'uid', 
    'status',
    'changed',
     'comment']);
  $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);
   $results = $pager->execute()->fetchAll();
  $results = $query->execute()->fetchAll();
  


  // dd($results);
  $rows = [];
  foreach ($results as $data) {
   
      $uid = $data->uid;
      if (!empty($uid)) {
          $account = \Drupal\user\Entity\User::load($uid); // pass your uid
          if (!empty($account) && $account != NULL) {
      
              $name = $account->getDisplayName();
          }
          else{
              $name = 'Anonymous';
          }
        
          
          
      }
      else{
          $name = 'Anonymous';
      }
     

      //get data
      $rows[] = [
          'nid' => $data->nid,
          'title' => $data->title,
          'type' => $data->type,
          'uid'=> $name,
          // 'uid'=>scf_logs_get_user_name($data->uid),
          'status' =>$statuses [$data->status],
          'changed' => date('D, m-d-Y  - h:i',$data->changed),
          'comment' => $data->comment,
      ];
  }

                                                                                                                          
  


  // render table
  $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,

      '#empty' => $this->t('There are no records found.'),
  ];


   $form['pager'] = array(
    '#type' => 'pager'
    );

    
    return $form;

  }
  
   /**
   * {@inheritdoc}
   */

    function _scf_logs_form_redirect(array &$form, FormStateInterface $form_state) {
      // $val = $form_state['values'];
    
      $val= $form_state->getValues();
      
      $type = isset($val['type']) ? $val['type'] : 'all';
      $status = isset($val['status']) ? $val['status'] : 'all';
      // $redirect =new RedirectResponse('scf-logs/'.$type.'/'.$status);
      // $redirect->send();

      
      $url = Url::fromRoute('scf_logs.scflogsfiltercontent', $route_parameters = ['type'=>$type,'status'=>$status]);
      $command = new RedirectResponse($url->toString());
       $command->send();
      // $path = 'admin/scf-logs/' . $type . '/' . $status;
      // $form_state['redirect'] = $path;
    }



    function _scf_logs_form_reset(array & $form, FormStateInterface $form_state) {
      $redirect =new RedirectResponse('/admin/scf-logs');
      $redirect->send();
    }

  public function validateForm(array & $form, FormStateInterface $form_state) {
    
		
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {
     
  }




}