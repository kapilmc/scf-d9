<?php
 
/**
 * @file
 * @Contains Drupal\apiexchange\Controller\ApiexchangeController.
 */

namespace Drupal\apiexchange\Controller;

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
 * Implement ApiexchangeController class operations.
 */
class ApiexchangeController extends ControllerBase
{
    // $form=\Drupal::formBuilder()->getForm(Drupal\apiexchange\Form\ApiexchangeEntityForm);
   
    public function mypage()
    {
        // return $form;
        // create table header
        $mypage = [
            'id' => $this->t('Key'),
            // 'key' => $this->t('Key'),
            'name' => $this->t('API Name'),
            'type' => $this->t('Type'),
            'url' => $this->t('Url'),
            'debug' => $this->t('Debug'),
            'edit' => $this->t('Edit'),
            'delete' => $this->t('Delete'),
        ];

        // get data from database
        
        $query = \Drupal::database()->select('apiexchange', 'm');
        $query->fields(
            'm', ['id', 'name', 'type', 'return_type', 'url', 'custom_header', 'detail', 'debug']
        );
        // $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(15);
        // $results = $pager->execute()->fetchAll();
        $results = $query->execute()->fetchAll();
        
        // dd($results);
        
        $rows = [];
        foreach ($results as $data) {
            // $url_delete = Url::fromRoute('demo.delete_form', ['id' => $data->id], []);
            $url_edit = Url::fromRoute('apiexchnage.apiexchangeentity_form', ['id' => $data->id]);
            // dd($url_edit);

            // $url_view = Url::fromRoute('demo.show_data', ['id' => $data->id], []);
            // $url_search = Url:apiexchnage.apiexchangeentity_editform:fromRoute('demo.search_data', ['name' => $data->name], []);
            // $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
            // $linkView = Link::fromTextAndUrl('View', $url_view);
             // $linkSearch = Link::fromTextAndUrl('Search', $url_search);

            //  dd($data);
            

            // get data
            $rows[] = [
                'id' => $data->id,
                // 'key' => $data->key,
                'name'=>$data->name,
                'type' => $data->type,
                'url' => $data->url,
                'debug' => $data->debug,
                'edit' =>  $linkEdit,
                // 'delete' => $linkDelete,
                // 'search' =>  $linkSearch
                // 'view' => $linkView,
            ];
        }
// dd($rows);
        // return array(
        // Your theme hook name.
        //   '#theme' => 'alldata',
        //   '#items' => $rows,
        // '#type' => '',
        // '#title' => $this->t(''),
        // '#pager'=> '$pager',
        // );
   

        // render table
        $form['table'] = [
            '#type' => 'table',
            '#header' => $mypage,
            '#rows' => $rows,

            '#empty' => $this->t('No data found'),
        ];


        //  $form['pager'] = array(
        //  '#type' => 'pager'
        //  );

           return $form;

    }

}