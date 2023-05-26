<?php
/**
 * @file
 * Contains \Drupal\csvImporter\Form\CsvImporterfForm.
 */
namespace Drupal\csvImporter\Form;
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

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;



/**
 * Implements form to upload a file and start the batch on form submit.
 *
 * @see \Drupal\Core\Form\FormBase
 * @see \Drupal\Core\Form\ConfigFormBase
 */

class csvImporter_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return ' csvImporter_form';
  }
  
  
  public function buildForm(array $form, FormStateInterface $form_state) {
   
    $validators = array(
      'file_validate_extensions' => array('csv'),
      );
      /* this field will use to uplaod the file */
      $form['csv_upload'] = array(
      '#type' => 'managed_file',
      '#name' => 'csv_upload',
      '#title' => t('XLSX File'),
      '#size' => 20,
      '#description' => 'Due to server restrictions, the <strong>maximum upload file size is 100 MB</strong>. Files that exceed this size will be disregarded.',
      '#upload_validators' => $validators,
      '#upload_location' => 'public://csv_upload/',
      );
      /* This button is used for import the file */
      $form['upload'] = array(
      '#type' => 'submit',
      '#value' => 'Commence Import',
      );









    return $form;
      
  }




  public function validateForm(array &$form, FormStateInterface $form_state)
  {
      if (empty($form_state->getValue('csv_upload')) && $form_state->getValue('op')=='Import') {
          $form_state->setErrorByName('csv_upload', $this->t('Only Csv file upload '));
      }
     
  }
 /**
     * {@inheritdoc}
     *
     * @return data
     */


  public function submitForm(array &$form, FormStateInterface $form_state) {

    // $val =  $form_state->getvalues();
    // dd($val);



    $file = \Drupal::entityTypeManager()->getStorage('file')
    ->load($form_state->getValue('csv_upload')[0]);
$full_path = $file->get('uri')->value;
$file_name = basename($full_path);
try{

$inputFileName = \Drupal::service('file_system')->realpath('public://csv_upload/'.$file_name);
// dd($inputFileName);
$spreadsheet = IOFactory::load($inputFileName); 
$sheetData = $spreadsheet->getActiveSheet();
// dd($sheetData);
$rows = array();
foreach ($sheetData->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    $cells = [];
    foreach ($cellIterator as $cell) {
          $cells[] = $cell->getValue();
    }
    $rows[] = $cells;
    // dd($rows);
}



    array_shift($rows);
    // dd($rows);
    $operations = [
      ['delete_nodes_process', [$rows]],
  ];
  $batch = [
      'title' => $this->t('Interting All Nodes ...'),
      'operations' => $operations,
      'finished' => 'delete_nodes_finished',
  ];
  batch_set($batch);
 
    \Drupal::messenger()->addMessage('Imported successfully');

}catch (Exception $e) {
    \Drupal::logger('type')->error($e->getMessage());






} 





}

}