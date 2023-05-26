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

$connection->insert('node_type')
->fields(array(
  'type',
  'name',
  'base',
  'module',
  'description',
  'help',
  'has_title',
  'title_label',
  'custom',
  'modified',
  'locked',
  'disabled',
  'orig_type',
))
->values(array(
  'type' => 'type_with_multifields',
  'name' => 'Type with multifields',
  'base' => 'node_content',
  'module' => 'node',
  'description' => 'A content type for testing the migration of multifield values.',
  'help' => '',
  'has_title' => '1',
  'title_label' => 'Title',
  'custom' => '1',
  'modified' => '1',
  'locked' => '0',
  'disabled' => '0',
  'orig_type' => 'type_with_multifields',
))
->execute();
