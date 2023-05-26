<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_competitive_insights_import_form.
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
 

class scfp_competitive_insights_import_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_competitive.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_competitive_insights_import_form';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      // static::SETTINGS,
      'scfp_competitive_insights.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_competitive_insights.settings');




    // drupal_set_title('Competitive insights Import');

    $form['scfp_cmpi_csv'] = array(
      '#title' => t('Upload CSV'),
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#default_value' => $config->get('scfp_cmpi_csv'),
      '#upload_location' => 'public://competitive_insights/',
      "#upload_validators"  => array("file_validate_extensions" => array("csv")),
      '#description' => 'Upload CSV file to import the competitive insights. The CSV file must be in the proper format. 
      <br/>Refer to the <a href="/sites/all/themes/scfp_admin/assets/competitive_insights_sample_csv_file.csv" target="_blank" download>Sample CSV file</a> for the format and column order.',
    );
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => t('Save & Import'),
    //   '#submit' => array('_scfp_competitive_insights_import_form_submit'),
    );
  
  
  
    return $form;


  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('scfp_competitive_insights.settings')
    // $val= $form_state->getvalues();

    // dd($val);

    ->set('scfp_cmpi_csv',$form_state->getValue('scfp_cmpi_csv'))
  
    ->save();

    // $val = $form_state['values'];

    // alt_vars_set('scfp_cmpi_csv', $val['scfp_cmpi_csv']);

    if ($val['scfp_cmpi_csv'] > 0) {
      $file = file_load($val['scfp_cmpi_csv']);
      // Change the status.
      $file->status = 1;
      file_usage_add($file, 'file', 'competitive_insights', 1);
      // Update the file status into the database.
       $f = file_save($file);
      _scfp_competitive_insights_import($f);
    }
    // drupal_set_message('Form has been saved successfully');
    \Drupal::messenger()->addMessage('Form has been saved successfully');
     
    
    }
  






    function _scfp_competitive_insights_import($file) {
        $batch = array(
          'operations' => array(),
          'finished' => 'competitive_insights_batch_finished',
          'title' => t('Batch import competitive insights'),
          'init_message' => t('Importing is starting...'),
          'progress_message' => t('Processed @current out of @total.'),
          'error_message' => t('Mapping has encountered an error.')
        );
      
        $file_path = str_replace('public://', '/', $file->uri);
        $path = DRUPAL_ROOT.'/sites/default/files'.$file_path;
        $handle = fopen($path, 'r');
        $header = TRUE;
        $fields =  [];
        while ( $line = fgetcsv($handle, 4096, ",") ) {
          if($header) {
            $fields = $line;
            $header = FALSE;
          } else {
            if(!empty($line[0])) {
              $batch['operations'][] = array('competitive_insights_batch_process', array(array_map('base64_encode', $line), $fields));
            }
          }
        }
        if(!empty($batch['operations'])) {
          deleteOldPublications();
        }
        batch_set($batch);
        batch_process('admin/content/competitive-insights');
       }
      
       function deleteOldPublications() {
        $nids = [];
        $results = Drupal::database()->select('node', 'n')
          ->fields('n', array('nid'))
          ->condition('type', 'publications')
          ->execute();
        $nids = $results->fetchCol();
        if (!empty($nids)) {
          node_delete_multiple($nids);
        }
      }
       
       
      






}











