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

use Drupal\redirect\Entity\Redirect;
use Drupal\Core\Render\Markup;

/**
 * Implement ApiexchangeController class operations.
 */
class ApiexchangeController extends ControllerBase
{

   
    public function mypage()
    {
        
        $form['add'] = [
            '#type' => 'link',
            '#title' =>$this->t('Add New Api'),
            '#url'=> url::fromRoute('apiexchnage.apiexchangeentity_form'),
        ];




        $header = [
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
            'm', ['id', 
            'name',
             'type',
              'return_type',
               'url',
                'custom_header',
                 'detail',
                  'debug']
        );
       
        $results = $query->execute()->fetchAll();
        
        // dd($results);
        
        $rows = [];
        foreach ($results as $data) {
            // $url_delete = Url::fromRoute('demo.delete_form', ['id' => $data->id], []);
            $url_edit = Url::fromRoute('apiexchnage.apiexchangeentity_editform', ['id' => $data->id]);
            // dd($url_edit);

            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
      
            

            // get data
            if ($data->debug ==0){
                $debug = "No";
            }else{$debug = "Yes";}
            $rows[] = [
                'id' => $data->id,
                // 'key' => $data->key,
                'name'=>$data->name,
                'type' => $data->type,
                'url' => $data->url,
                'debug' =>$debug,
                'edit' =>  $linkEdit,
           
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
            '#header' => $header,
            '#rows' => $rows,

            '#empty' => $this->t('No data found'),
        ];


     

           return $form;

    }

}