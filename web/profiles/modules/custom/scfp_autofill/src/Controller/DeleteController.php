<?php
 
/**
 * @file
 * @Contains Drupal\scfp_autofill\Controller\DeleteController.
 */

namespace Drupal\scfp_autofill\Controller;

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
 * Implement DeleteController class operations.
 */
class DeleteController extends ControllerBase
{

    public function mypage()
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\scfp_autofill\Form\ScfpAutofillForm');
        // $title = \Drupal::request()->query->get('search');

        $query = \Drupal::database()->select('scfp_autofill', 'm');
        $query->fields(
            'm', ['id', 
            'title',
            'created',
            'changed',
            'count']
        );
        $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);
        $results = $pager->execute()->fetchAll();
        $results = $query->execute()->fetchAll();
        
        // dd($results);
        
        $rows = [];
        foreach ($results as $data) {
            $url_delete = Url::fromRoute('scfp_autofill.delete', ['id' => $data->id], []);
            $url_edit = Url::fromRoute('scfp_autofill.openid_connect_auto_login', ['id' => $data->id]);
            
            
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
           
            //  dd($data);
            

            // get data
            $rows[] = [
              
                'title' => $data->title,
                'edit' =>  $linkEdit,
                'delete' => $linkDelete,
              
            ];
        }
        // dd($rows);
    // create table header
    $header = [
        'title' => $this->t('Title'),
        'edit' => $this->t('Edit'),
        'delete' => $this->t('Delete'),
    ];
    // dd($header );
        // render table

        // if($title) {
        //     $form['table'] = [
        //     '#type' => 'table',
        //     '#header' => $header,
           
        //     '#rows' => get_stuff("", $title),

        //     '#empty' => $this->t('No data found'),
        //     ];
        // }
        // else{
        //     $form['table'] = [
        //     '#type' => 'table',
        //     '#header' => $header,
        //     '#rows' => get_stuff("All", $title),

        //     '#empty' => $this->t('No data found'),
        //     ];
        // }

  
    // dd($mypage);

        $form['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,

            '#empty' => $this->t('No data found'),
            ];
         $form['pager'] = array(
         '#type' => 'pager'
         );
            return $form;

    }

}