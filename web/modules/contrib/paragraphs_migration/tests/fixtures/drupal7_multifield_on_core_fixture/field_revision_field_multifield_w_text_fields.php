<?php
// @codingStandardsIgnoreFile
/**
 * @file
 * A database agnostic dump for testing purposes.
 *
 * This file was generated by the Drupal 9.1.9 db-tools.php script.
 */

use Drupal\Core\Database\Database;

$connection = Database::getConnection();

$connection->schema()->createTable('field_revision_field_multifield_w_text_fields', array(
  'fields' => array(
    'entity_type' => array(
      'type' => 'varchar',
      'not null' => TRUE,
      'length' => '128',
      'default' => '',
    ),
    'bundle' => array(
      'type' => 'varchar',
      'not null' => TRUE,
      'length' => '128',
      'default' => '',
    ),
    'deleted' => array(
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'tiny',
      'default' => '0',
    ),
    'entity_id' => array(
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'unsigned' => TRUE,
    ),
    'revision_id' => array(
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'unsigned' => TRUE,
    ),
    'language' => array(
      'type' => 'varchar',
      'not null' => TRUE,
      'length' => '32',
      'default' => '',
    ),
    'delta' => array(
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'unsigned' => TRUE,
    ),
    'field_multifield_w_text_fields_field_text_plain_value' => array(
      'type' => 'varchar',
      'not null' => FALSE,
      'length' => '255',
    ),
    'field_multifield_w_text_fields_field_text_plain_format' => array(
      'type' => 'varchar',
      'not null' => FALSE,
      'length' => '255',
    ),
    'field_multifield_w_text_fields_field_text_sum_filtered_value' => array(
      'type' => 'text',
      'not null' => FALSE,
      'size' => 'big',
    ),
    'field_multifield_w_text_fields_field_text_sum_filtered_summary' => array(
      'type' => 'text',
      'not null' => FALSE,
      'size' => 'big',
    ),
    'field_multifield_w_text_fields_field_text_sum_filtered_format' => array(
      'type' => 'varchar',
      'not null' => FALSE,
      'length' => '255',
    ),
    'field_multifield_w_text_fields_id' => array(
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'unsigned' => TRUE,
    ),
  ),
  'primary key' => array(
    'entity_type',
    'entity_id',
    'revision_id',
    'deleted',
    'delta',
    'language',
  ),
  'indexes' => array(
    'entity_type' => array(
      'entity_type',
    ),
    'bundle' => array(
      'bundle',
    ),
    'deleted' => array(
      'deleted',
    ),
    'entity_id' => array(
      'entity_id',
    ),
    'revision_id' => array(
      'revision_id',
    ),
    'language' => array(
      'language',
    ),
    'field_multifield_w_text_fields_field_text_plain_format' => array(
      'field_multifield_w_text_fields_field_text_plain_format',
    ),
    'field_multifield_w_text_fields_field_text_sum_filtered_format' => array(
      'field_multifield_w_text_fields_field_text_sum_filtered_format',
    ),
    'field_multifield_w_text_fields_id' => array(
      'field_multifield_w_text_fields_id',
    ),
  ),
  'mysql_character_set' => 'utf8',
));

$connection->insert('field_revision_field_multifield_w_text_fields')
->fields(array(
  'entity_type',
  'bundle',
  'deleted',
  'entity_id',
  'revision_id',
  'language',
  'delta',
  'field_multifield_w_text_fields_field_text_plain_value',
  'field_multifield_w_text_fields_field_text_plain_format',
  'field_multifield_w_text_fields_field_text_sum_filtered_value',
  'field_multifield_w_text_fields_field_text_sum_filtered_summary',
  'field_multifield_w_text_fields_field_text_sum_filtered_format',
  'field_multifield_w_text_fields_id',
))
->values(array(
  'entity_type' => 'node',
  'bundle' => 'type_with_multifields',
  'deleted' => '0',
  'entity_id' => '112',
  'revision_id' => '119',
  'language' => 'en',
  'delta' => '0',
  'field_multifield_w_text_fields_field_text_plain_value' => 'Content with multifields text plain copy [delta0] [rev1]',
  'field_multifield_w_text_fields_field_text_plain_format' => NULL,
  'field_multifield_w_text_fields_field_text_sum_filtered_value' => 'Content with multifields text summary copy [delta0] [rev1]',
  'field_multifield_w_text_fields_field_text_sum_filtered_summary' => '',
  'field_multifield_w_text_fields_field_text_sum_filtered_format' => 'filtered_html',
  'field_multifield_w_text_fields_id' => '2',
))
->values(array(
  'entity_type' => 'node',
  'bundle' => 'type_with_multifields',
  'deleted' => '0',
  'entity_id' => '112',
  'revision_id' => '119',
  'language' => 'en',
  'delta' => '1',
  'field_multifield_w_text_fields_field_text_plain_value' => 'Content with multifields text plain copy [delta1] [rev1]',
  'field_multifield_w_text_fields_field_text_plain_format' => NULL,
  'field_multifield_w_text_fields_field_text_sum_filtered_value' => 'Content with multifields text summary copy [delta1] [rev1]',
  'field_multifield_w_text_fields_field_text_sum_filtered_summary' => '',
  'field_multifield_w_text_fields_field_text_sum_filtered_format' => 'filtered_html',
  'field_multifield_w_text_fields_id' => '3',
))
->values(array(
  'entity_type' => 'node',
  'bundle' => 'type_with_multifields',
  'deleted' => '0',
  'entity_id' => '112',
  'revision_id' => '120',
  'language' => 'en',
  'delta' => '0',
  'field_multifield_w_text_fields_field_text_plain_value' => 'Content with multifields text plain copy [delta0] [rev2]',
  'field_multifield_w_text_fields_field_text_plain_format' => NULL,
  'field_multifield_w_text_fields_field_text_sum_filtered_value' => 'Content with multifields text summary copy [delta0] [rev2]',
  'field_multifield_w_text_fields_field_text_sum_filtered_summary' => '',
  'field_multifield_w_text_fields_field_text_sum_filtered_format' => 'filtered_html',
  'field_multifield_w_text_fields_id' => '2',
))
->values(array(
  'entity_type' => 'node',
  'bundle' => 'type_with_multifields',
  'deleted' => '0',
  'entity_id' => '112',
  'revision_id' => '120',
  'language' => 'en',
  'delta' => '1',
  'field_multifield_w_text_fields_field_text_plain_value' => 'Content with multifields text plain copy [delta1] [rev2]',
  'field_multifield_w_text_fields_field_text_plain_format' => NULL,
  'field_multifield_w_text_fields_field_text_sum_filtered_value' => 'Content with multifields text summary copy [delta1] [rev2]',
  'field_multifield_w_text_fields_field_text_sum_filtered_summary' => '',
  'field_multifield_w_text_fields_field_text_sum_filtered_format' => 'filtered_html',
  'field_multifield_w_text_fields_id' => '3',
))
->values(array(
  'entity_type' => 'taxonomy_term',
  'bundle' => 'vocabulary_with_multifields',
  'deleted' => '0',
  'entity_id' => '126',
  'revision_id' => '126',
  'language' => 'fr',
  'delta' => '0',
  'field_multifield_w_text_fields_field_text_plain_value' => 'Multifield term "text plain" copy [FR]',
  'field_multifield_w_text_fields_field_text_plain_format' => NULL,
  'field_multifield_w_text_fields_field_text_sum_filtered_value' => "Multifield term \"text summary filtered\" summary [FR]\r\n<!--break-->\r\nMultifield term \"text summary filtered\" copy [FR]",
  'field_multifield_w_text_fields_field_text_sum_filtered_summary' => '',
  'field_multifield_w_text_fields_field_text_sum_filtered_format' => 'filtered_html',
  'field_multifield_w_text_fields_id' => '1',
))
->values(array(
  'entity_type' => 'taxonomy_term',
  'bundle' => 'vocabulary_with_multifields',
  'deleted' => '0',
  'entity_id' => '126',
  'revision_id' => '126',
  'language' => 'is',
  'delta' => '0',
  'field_multifield_w_text_fields_field_text_plain_value' => 'Multifield term "text plain" copy [IS - default]',
  'field_multifield_w_text_fields_field_text_plain_format' => NULL,
  'field_multifield_w_text_fields_field_text_sum_filtered_value' => "Multifield term \"text summary filtered\" summary [IS - default]\r\n<!--break-->\r\nMultifield term \"text summary filtered\" copy [IS - default]",
  'field_multifield_w_text_fields_field_text_sum_filtered_summary' => '',
  'field_multifield_w_text_fields_field_text_sum_filtered_format' => 'filtered_html',
  'field_multifield_w_text_fields_id' => '1',
))
->execute();
