<?php
 
/**
 * @file
 * @Contains Drupal\scf_logs\Controller\scf_logsController.
 */

namespace Drupal\scf_logs\Controller;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Controller\ControllerBase;
use Drupal\myblock\Entity\MyBlock;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AddCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


use Drupal\Core\Render\Markup;

/**
 * Implement scf_logs class operations.
 */
class scf_logsController extends ControllerBase {

 
 public function scf_logs_data($type = 'all', $status = 'all') {
    // dd($type);
    $build = [];

    $build['form'] = \Drupal::formBuilder()->getForm('\Drupal\scf_logs\Form\scf_logs_form');
   // $build['form']['actions']['apply']['#submit'][0][1] = '_scf_logs_form_redirect_second';
    // dd($build);
    unset($build['form']['table']);
    unset($build['form']['pager']);
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
    $query = \Drupal::database()->select('scf_logs', 'd');
    if ($type != 'all') {
        $query->condition('type', $type);
    }
    if ($status != 'all') {
    $query->condition('status', $status);
    }


    $query->fields('d', ['nid','title','type','uid','status','changed','comment']);
     $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);
       $pager->execute()->fetchAll();
        $results = $query->execute()->fetchAll();
     //    dd($results);
        $rows = [];
        foreach ($results as $data) {
            $uid = $data->uid;
            if (!empty($uid)) {
                $account = \Drupal\user\Entity\User::load($uid); // pass your uid
                if (!empty($account) && $account != NULL) {
            
                    $name = $account->getDisplayName();
                }else{
                    $name = 'Unknown';
                }
                
            }else{
                $name = 'Unknown';
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
        $build['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,

            '#empty' => $this->t('There are no records found.'),
        ];

        $build['pager'] = array(
            '#type' => 'pager'
          );

        if (empty($build['table']['#rows'])) {
           
           unset($build['pager']);
        }
        // dd($build);
        return $build;

      

    }


}





