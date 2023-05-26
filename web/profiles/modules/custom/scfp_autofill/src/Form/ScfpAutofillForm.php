<?php
namespace Drupal\scfp_autofill\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Link;
use Drupal\file\Entity\File;
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
 * Class ScfpAutofillForm.
 *
 * @package Drupal\scfp_autofill\Form
 */
class ScfpAutofillForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'scfpautofill_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $getQuery = \Drupal::request()->query->get('search');
        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['id'])) {
            $query = $conn->select('scfp_autofill', 'm')
                ->condition('id', $_GET['id'])
                ->fields('m');
            $record = $query->execute()->fetchAssoc();
        }
        $form['title'] = [
        '#title' => 'Keywords',
        '#type' => 'textarea',
        '#rows' => 3,
        '#description' => 'Enter comma(,) separted keywords',
        '#default_value' => (isset($record['title']) && $_GET['id']) ? $record['title']:'',
        ];
        $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
        ];
        $validators = array(
        'file_validate_extensions' => array('csv'),
        );
        $form['csv_upload'] = array(
        '#type' => 'managed_file',
        '#name' => 'csv_upload',
        '#title' => t('CSV File'),
        '#size' => 20,
        '#description' => 'Due to server restrictions, the <strong>maximum upload file size is 100 MB</strong>. Files that exceed this size will be disregarded.',
        '#upload_validators' => $validators,
        '#upload_location' => 'public://content/csv_upload/',
        );
        $form['upload'] = array(
        '#type' => 'submit',
        '#value' => 'Import',
          '#submit' => array([$this,'csvimportdata']),
        );

        $form['#attributes'] = [
            'enctype' => 'multipart/form-data'
        ];




        $form['filters']['search'] = [
            '#title'         => 'Search',
            '#type'          => 'search',
            '#default_value' => (isset($getQuery)) ? $getQuery:'',
            
            ];
            $form['filters']['actions'] = [
            '#type'       => 'actions'
            ];
    
            $form['filters']['actions']['submit'] = [
            '#type'  => 'submit',
            '#value' => $this->t('Search'),
            '#submit' => array([$this,'Scfp_Autofill_Search']),
            ];
          
        // return $form;
    // get all data Scfp_Autofill

    
    if (!empty($getQuery)) {
       
      $database = \Drupal::database();
      $results = $database->select('scfp_autofill', 'm')
      ->fields('m', ['id', 'title','created','changed','count'])
      ->condition('title', "%" . $database->escapeLike($getQuery) . "%", 'LIKE')
      ->execute()
      ->fetchAll();
        
        $rows = [];
        foreach ($results as $data) {
            
            $url_delete = Url::fromRoute('scfp_autofill.delete', ['id' => $data->id], []);
            $url_edit = Url::fromRoute('scfp_autofill.openid_connect_auto_login', ['id' => $data->id]);
        
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
           
            // get data
            $rows[] = [
                'title' => $data->title,
                'edit' =>  $linkEdit,
                'delete' => $linkDelete,
              
            ];
        }

    }else{


    
        // $database = \Drupal::database();
        // $results = $database->select('scfp_autofill', 'm')
        // ->fields('m', ['id', 'title','created','changed','count'])
        // ->execute()
        // ->fetchAll();

        // $pager = $database->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);

        // $results = $pager->execute()->fetchAll();
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
      



        $rows = [];
        foreach ($results as $data) {
            $url_delete = Url::fromRoute('scfp_autofill.delete', ['id' => $data->id], []);
            $url_edit = Url::fromRoute('scfp_autofill.openid_connect_auto_login', ['id' => $data->id]);
        
            $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
            $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);

            // get data
            $rows[] = [
                'title' => $data->title,
                'edit' =>  $linkEdit,
                'delete' => $linkDelete,
              
            ];
        }
    }

        

        // create table header
      $header = [
        'title' => $this->t('Title'),
        'edit' => $this->t('Edit'),
        'delete' => $this->t('Delete'),
    ];
      

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
    
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
           //Save functionality

        $field=$form_state->getValues();
        $title=$field['title'];
       
        if (isset($_GET['id'])) {
            $field  = array(
              'title'   => $title,
            );
            $query = \Drupal::database();
            $query->update('scfp_autofill')
                ->fields($field)
                ->condition('id', $_GET['id'])
                ->execute();
            \Drupal::messenger()->addStatus($this->t('Form has been saved successfully'));
            $form_state->setRedirect('scfp_autofill.openid_connect_auto_login');
        }
        else
        {
            $field  = array(
             'title'   => $title,
           
            );
            $query = \Drupal::database();
            $query ->insert('scfp_autofill')
                ->fields($field)
                ->execute();

            \Drupal::messenger()->addStatus($this->t('Form has been saved successfully'));
            $response = new RedirectResponse("/admin/managing/manage-autofill-keywords");
            $response->send();

        }
    }



    /**
     * {@inheritdoc}
     */
    public function csvimportdata(array &$form, FormStateInterface $form_state)
    {
   
        // dd('sdjvbjfhsdb');


        $file = \Drupal::entityTypeManager()->getStorage('file')
        ->load($form_state->getValue('csv_upload')[0]); // Just FYI. The file id will be stored as an array
      $full_path = $file->get('uri')->value;
     $file_name = basename($full_path);

      try{
        $inputFileName = \Drupal::service('file_system')->realpath('public://content/csv_upload/'.$file_name);

        $spreadsheet = IOFactory::load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet();

        $rows = array();
        foreach ($sheetData->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); 
            $cells = [];
            foreach ($cellIterator as $cell) {
                  $cells[] = $cell->getValue();
                  
            }
            $rows[] = $cells;
   
        }
        array_shift($rows);
        $query = \Drupal::database();
        foreach ($rows as $row) {

            $query ->insert('scfp_autofill')
                ->fields(['title'=>$row[1]])
                ->execute();
        }

        \Drupal::messenger()->addMessage('Imported successfully');

    }catch (Exception $e) {
        \Drupal::logger('type')->error($e->getMessage());
    } 




    }

 /**
     * {@inheritdoc}
     */
    public function Scfp_Autofill_Search(array &$form, FormStateInterface $form_state)
    {

        // // dd('sdmnvbsd');
     
        $field = $form_state->getValues();
        // dd($field);
        $search = $field["search"];
        $url = \Drupal\Core\Url::fromRoute('scfp_autofill.openid_connect_auto_login')
        // $response = new RedirectResponse("/admin/managing/manage-autofill-keywords");
          ->setRouteParameters(array('search'=>$search));
        $form_state->setRedirectUrl($url); 




        $res = array();
        if($opt == "All"){	
         $results = \Drupal::database()->select('scfp_autofill', 'm')
         ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
         ->limit(100);
         $results->fields('m');
         $results->orderBy('m.id','DESC');
         $res = $results->execute()->fetchAll();
         $ret = [];
        }else{
            $results = \Drupal::database()->select('scfp_autofill', 'm')
         ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
         ->limit(15);
         $results->fields('m');
         $results->orderBy('m.id','DESC');
         $results->condition('title', '%' . $title . '%', 'LIKE');
         $res = $results->execute()->fetchAll();
        //  dd($res);
         $ret = [];
        }
           foreach ($res as $row) {
               $url_delete = Url::fromRoute('scfp_autofill.delete', ['id' => $row->id]);
               $url_edit = Url::fromRoute('scfp_autofill.openid_connect_auto_login', ['id' => $row->id]);
               
               $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
               $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
       
             $ret[] = [
               // 'id' => $row->id,
               'title' => $row->title,
               'edit' =>  $linkEdit,
               'delete' => $linkDelete,
            ];
           }
        //    dd($ret);
           return $ret;






    }

   
















}