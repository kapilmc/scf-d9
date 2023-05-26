<?php
 
/**
 * @file
 * @Contains Drupal\email_template\Controller\EmailTemplateController.
 */

namespace Drupal\email_template\Controller;

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
 * Implement Demo class operations.
 */
class EmailTemplateController extends ControllerBase {

 
   



 public function emaildata() {

        // return $form;
        // create table header
        $emailtemplate= [
            
            'name' => $this->t('Name'),
            'edit' => $this->t('Actions'),
            
            


        ];



        // get data from database
        
        $query = \Drupal::database()->select('email_template_details', 'm');
        $query->fields('m', ['id', 'name', 'body']);
        $results = $query->execute()->fetchAll();
        
        // // dd($results);


             $rows = [];
        foreach ($results as $data) {
      
            $url_edit = Url::fromRoute('email_template.UpdateForm', ['id' => $data->id], []);
      
             $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
            
            //$edit   = Url::fromUserInput('/admin/config/content/templates?id='.$data->id);
  
     


        //     //get data
            $rows[] = [
                // 'id' => $data->id,
                'name' => $data->name,
                // 'body' => $data->body,
                 'edit' =>  $linkEdit,
            //Drupal::l('edit', $edit),
                
            ];
        }

                                                                                                                                
        // return array (
        //     '#theme' => 'scfphomepage',
        //     '#version' => 'Drupal8',
        //   );
      


        // render table
        $form['table'] = [
            '#type' => 'table',
            '#header' =>$emailtemplate,
            '#rows' => $rows,

            '#empty' => $this->t('No Records Found'),
        ];

               return $form;

      

    }
}




