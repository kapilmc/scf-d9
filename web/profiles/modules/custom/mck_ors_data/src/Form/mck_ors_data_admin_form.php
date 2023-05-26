<?php
/**
 * @file
 * Contains \Drupal\mck_ors_data\Form\mck_ors_data_admin_form.
 */
namespace Drupal\mck_ors_data\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;

class mck_ors_data_admin_form extends ConfigFormBase{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mck_ors_data_admin_form';
  }
   /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['mck_ors_data_admin.settings'];
    }
  
    /**
     * {@inheritdoc}
     */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mck_ors_data_admin.settings');

   

    $form['settings'] = array(
        '#type' => 'fieldset',
        '#title' => t('Settings'),
        '#collapsible' => TRUE,
      );
      $form['settings']['ors_database_username'] = array(
        '#type' => 'textfield',
        '#title' =>  t('Username'),
        '#size' => 60,
        '#maxlength' => 128,
        '#required' => TRUE,
        // '#default_value' => (isset($data['body'])) ? $data['ors_database_username'] : '',
        '#description' => t('ORS Database Username'),
        '#default_value' => $config->get('ors_database_username'),
    
      );
      $form['settings']['ors_database_password'] = array(
        '#type' => 'textfield',
        '#title' =>  t('Password'),
        '#size' => 60,
        '#maxlength' => 128,
        // '#default_value' => (isset($data['body'])) ? $data['ors_database_password'] : '',
        '#description' => t('ORS Database Password'),
        '#default_value' => $config->get('ors_database_password'),
      );
      $form['settings']['ors_database_connection_string'] = array(
        '#type' => 'textfield',
        '#title' =>  t('Easy Connect String'),
        '#size' => 60,
        '#maxlength' => 128,
        '#required' => TRUE,
        // '#default_value' => (isset($data['body'])) ? $data['ors_database_connection_string'] : '',
        '#description' => t('ORS Database Easy Connect string. Easy connect string have the <i>[//]host_name[:port][/service_name][:server_type][/instance_name]</i> format. See !url detail about Easy connect string.', array('!url' => '<a href="http://php.net/manual/en/function.oci-connect.php">'. t('oci_connect') .'</a>')),
        '#default_value' => $config->get('ors_database_connection_string', '//hostname:port/service_name:server_type/instance_name'),
      );




      return parent::buildForm($form, $form_state);
    }






  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      $this->config('mck_ors_data_admin.settings')
          ->set($key, $value)->save();

      parent::submitForm($form, $form_state);
  }


  }



}





// class ORSDbHandler {
//   private static $_ORSDBHandle;
//   private function __construct() {}

//   public static function getHandle () {
//     if (!isset(self::$_ORSDBHandle)) {
//       $username = variable_get('ors_database_username', 'username');
//       $password = variable_get('ors_database_password', 'password');
//       $connection_string = variable_get('ors_database_connection_string', '');
//       self::$_ORSDBHandle = oci_connect ($username, $password, "$connection_string");
//     }
//     return self::$_ORSDBHandle;
//   }
// }


// /**
// * Implements hook_permission().
// */
// function mck_ors_data_permission() {
//   return array(
//     'ors data update' => array(
//       'title' => t('Ors data update'),
//       'description' => t('Update ORS data'),
//     ),
//   );
// }

// /**
// * Implements hook_menu().
// */
//  function mck_ors_data_menu() {
//    $items['admin/config/ors-data/ors-data-update'] = array(
//      'title' => 'ORS Data',
//      'page callback' => 'mck_ors_data_ors_data_page',
//      'page arguments' => array('mck_ors_data_ors_data_settings_form'),
//      'access arguments' => array('ors data update'),
//      'file' => 'mck_ors_data.admin.inc',
//    );
//    return $items;
//  }

//  /**
//   * Function for reading json data file.
//   */
//  function _strategy_read_json($uri) {
//    $realpath = drupal_realpath($uri);
//    $data = file_get_contents($realpath);
//    return $data;
//  }

//  /**
//   * Implements hook_cronapi().
//   */
//  function mck_ors_data_cronapi($op, $job = NULL) {
//    $items = array();
//    $items['fetch_ors_user'] = array(
//      'description' => 'Fetch users from ors data',
//      // Run this every 5 times.
//      'rule' => '*/5 * * * *',
//      'callback' => '_strategy_invite_get_all_users',
//    );
//    return $items;
//  }



//  /**
//   * Store all users from ORS in a array.
//   */
//  function _strategy_invite_get_all_users() {
//    if (!$connection = get_ors_connection()) {
//      return;
//    }
//    $query = "select * from psn_email pe join psn_person pp on pe.PERSON_ID = pp.PERSON_ID where pe.email_type = 'LN_Internet'";
//    $stid = oci_parse($connection, $query);
//    $execute = oci_execute($stid);
//    $row_count = ocirowcount($stid);
//    $data = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC);
//    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
//      $final_data[]= array('email' => $row['EMAIL_ID'],'name' => $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'], 'person_id' => $row['PERSON_ID']);
//    }
//    if ($final_data) {
//      $query = db_select('users', 'u');
//      $query->leftjoin('field_data_field_person_id', 'p', 'p.entity_id = u.uid');
//      $query->fields('p', array('field_person_id_value'));
//      $query->fields('u', array('mail', 'name'));
//      $query->condition('uid', '0', '!=');
//      $data = $query->execute();
//      $results = $data->fetchAll();
//      foreach ($results as $result) {
//       $user_db[] = array('email' => $result->mail,'name' => $result->name,'person_id' => $result->field_person_id_value);
//      }

//      if($user_db) {
//        $exact_data = array_diff_assoc($final_data, $user_db);
//      } else {
//        $exact_data = $final_data;
//      }

//      $final_exact_data = array_values($exact_data);
//      _strategy_invite_create_json_file($final_exact_data);
//    }
//  }

//  /**
//   * Create json file of the all ors user.
//   */
//  function _strategy_invite_create_json_file($final_data) {
//    $json = drupal_json_encode($final_data);
//    if (json_decode($json) != NULL) {
//      // Creating tmp folder.
//      if (!file_exists("public://tmp")) {
//        $tmp_dir = 'public://tmp/';
//        $tmp_dir = file_prepare_directory($tmp_dir, FILE_CREATE_DIRECTORY);
//      }

//      // Creating people_json folder.
//      if (!file_exists("public://people_json")) {
//        $json_dir = 'public://people_json/';
//        $json_dir = file_prepare_directory($json_dir, FILE_CREATE_DIRECTORY);
//      }

//      // Creating people_json file.
//      if (!file_exists("public://people_json/people_json.json")) {
//        $path = "public://people_json/people_json.json";
//        $file = file_unmanaged_save_data($json, $path, FILE_EXISTS_REPLACE);
//      }
//      // For not first time.
//      else {

//        if (!$connection = get_ors_connection()) {
//          return;
//        }
//        $query = "select count(*) from psn_email pe join psn_person pp on pe.PERSON_ID = pp.PERSON_ID where pe.email_type = 'LN_Internet'";

//        $stid = oci_parse($connection, $query);
//        $execute = oci_execute($stid);
//        $row_count = ocirowcount($stid);
//        $data = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC);

//        // Reading existing json file.
//        $uri = 'public://people_json/people_json.json';
//        $read_json_file = _strategy_read_json($uri);
//        $json_decode_data = drupal_json_decode($read_json_file);

//        if ($data['COUNT(*)'] != count($json_decode_data)) {
//          $path = "public://people_json/people_json.json";
//          $file = file_unmanaged_save_data($json, $path, FILE_EXISTS_REPLACE);
//        }
//      }
//    }
//  }

// /*
// * ORS connect handler return ORS object if things goes fine.
// */
// function get_ors_connection(){
//   try{
//     if (function_exists('oci_connect')) {
//       $c = ORSDbHandler::getHandle();
//       if ( ! $c ) {
//         drupal_set_message(t('Unable to connect check settings.'), 'warning');
//         return '';
//       }
//       else{
//         return $c;
//       }
//     }
//     else{
//       drupal_set_message(t('oci_connect function not existes. oci8 php extension is not installed or enabled.'), 'warning', FALSE);
//       return '';
//     }
//   }
//   catch(Exception $e){
//     drupal_set_message(t('Exception'), 'status', FALSE);
//     drupal_set_message(t('Message: ' .$e->getMessage()), 'status', FALSE);
//   }
// }
