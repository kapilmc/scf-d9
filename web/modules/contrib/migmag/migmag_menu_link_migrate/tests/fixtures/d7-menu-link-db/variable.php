<?php
// phpcs:ignoreFile
/**
 * @file
 * A database agnostic dump for testing purposes.
 *
 * This file was generated by the Drupal 9.2.9 db-tools.php script.
 */

use Drupal\Core\Database\Database;

$connection = Database::getConnection();

$connection->schema()->createTable('variable', array(
  'fields' => array(
    'name' => array(
      'type' => 'varchar',
      'not null' => TRUE,
      'length' => '128',
      'default' => '',
    ),
    'value' => array(
      'type' => 'blob',
      'not null' => TRUE,
      'size' => 'big',
    ),
  ),
  'primary key' => array(
    'name',
  ),
  'mysql_character_set' => 'utf8',
));

$connection->insert('variable')
->fields(array(
  'name',
  'value',
))
->values(array(
  'name' => 'clean_url',
  'value' => 'b:1;',
))
->values(array(
  'name' => 'cron_key',
  'value' => 's:43:"UyoPDIiMPthNhYXEk6lk3uUvZnDXfrYmfgxH0dWLnvk";',
))
->values(array(
  'name' => 'cron_last',
  'value' => 'i:1634129089;',
))
->values(array(
  'name' => 'css_js_query_string',
  'value' => 's:6:"r0vcly";',
))
->values(array(
  'name' => 'date_default_timezone',
  'value' => 's:3:"UTC";',
))
->values(array(
  'name' => 'drupal_private_key',
  'value' => 's:43:"dsJtqcibtEt-n4tXcP9Bmt6bcMRDWkpy8p4uExEzbXU";',
))
->values(array(
  'name' => 'entity_cache_tables_created',
  'value' => 'N;',
))
->values(array(
  'name' => 'install_profile',
  'value' => 's:7:"minimal";',
))
->values(array(
  'name' => 'install_task',
  'value' => 's:4:"done";',
))
->values(array(
  'name' => 'install_time',
  'value' => 'i:1634045687;',
))
->values(array(
  'name' => 'menu_expanded',
  'value' => 'a:0:{}',
))
->values(array(
  'name' => 'menu_masks',
  'value' => 'a:21:{i:0;i:125;i:1;i:121;i:2;i:63;i:3;i:62;i:4;i:61;i:5;i:60;i:6;i:44;i:7;i:42;i:8;i:31;i:9;i:30;i:10;i:24;i:11;i:21;i:12;i:15;i:13;i:14;i:14;i:11;i:15;i:7;i:16;i:6;i:17;i:5;i:18;i:3;i:19;i:2;i:20;i:1;}',
))
->values(array(
  'name' => 'site_default_country',
  'value' => 's:0:"";',
))
->values(array(
  'name' => 'site_mail',
  'value' => 's:17:"admin@example.com";',
))
->values(array(
  'name' => 'site_name',
  'value' => 's:10:"Menu Links";',
))
->values(array(
  'name' => 'theme_default',
  'value' => 's:6:"bartik";',
))
->values(array(
  'name' => 'user_register',
  'value' => 'i:2;',
))
->execute();