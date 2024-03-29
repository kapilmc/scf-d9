<?php

namespace Drupal\views_migration\Plugin\migrate\source\d6;

use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Plugin\MigrateIdMapInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Database\Database;
use Drupal\views_migration\Plugin\migrate\source\BaseViewsMigration;

/**
 * Drupal 6 views source from database.
 *
 * @MigrateSource(
 *   id = "d6_views_migration",
 *   source_module = "views"
 * )
 */
class ViewsMigration extends BaseViewsMigration {

  /**
   * D9 Table.
   *
   * @var siteTables
   */
  protected $siteTables;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $state);
    $this->siteTables = array_keys($this->viewsData);
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('views_view', 'vv')
      ->fields('vv', [
        'vid', 'name', 'description', 'tag', 'base_table',
      ]);
    $query->addField('vv', 'name', 'human_name');
    return $query;
  }

  /**
   * ViewsMigration prepareRow.
   *
   * @param \Drupal\migrate\Row $row
   *   The migration source ROW.
   */
  public function prepareRow(Row $row) {
    $display_plugin_map = [
      'views_data_export' => 'data_export',
    ];
    $this->row = $row;
    $this->view = $row->getSourceProperty('name');
    $vid = $row->getSourceProperty('vid');
    $base_table = $row->getSourceProperty('base_table');
    if ($base_table == 'commerce_product') {
      $base_table = 'commerce_product_variation';
    }
    $available_views_tables = array_keys($this->viewsData);
    $name = $row->getSourceProperty('name');
    $name = strtolower($name);
    $name = preg_replace('/[^a-zA-Z_]/s', '_', $name);
    $row->setSourceProperty('name', $name);
    try {
      if (!in_array($base_table, $available_views_tables)) {
        throw new MigrateSkipRowException('The views base table ' . $base_table . ' is not exist in your database.');
      }
    }
    catch (MigrateSkipRowException $e) {
      $skip = TRUE;
      $save_to_map = $e->getSaveToMap();
      if ($message = trim($e->getMessage())) {
        $this->idMap->saveMessage($row->getSourceIdValues(), $message, MigrationInterface::MESSAGE_INFORMATIONAL);
      }
      if ($save_to_map) {
        $this->idMap->saveIdMapping($row, [], MigrateIdMapInterface::STATUS_IGNORED);
        $this->currentRow = NULL;
        $this->currentSourceIds = NULL;
      }
      return FALSE;
    }
    $query = $this->select('views_display', 'vd')
      ->fields('vd', [
        'id', 'display_title', 'display_plugin', 'display_options', 'position',
      ]);
    $query->condition('vid', $vid);
    $execute = $query->execute();
    $relationships = $query->execute();
    $this->viewsRelationshipData = [];
    while ($viewsDisplayData = $relationships->fetchAssoc()) {
      $display_options = unserialize($viewsDisplayData['display_options'], ['allowed_classes' => FALSE]);
      if (isset($display_options['relationships'])) {
        foreach ($display_options['relationships'] as $relationshipId => $relationshipData) {
          $this->viewsRelationshipData[$relationshipId] = $relationshipData;
        }
      }
    }
    $display = [];
    $entity_base_table = '';
    $entity_type = '';
    $base_field = NULL;
    if (isset($this->baseTableArray[$base_table])) {
      $entity_detail = $this->baseTableArray[$base_table];
      $entity_base_table = $entity_detail['data_table'];
      $entity_type = $entity_detail['entity_id'];
      $base_field = $entity_detail['entity_keys']['id'];
    }
    else {
      $entity_base_table = $base_table;
      $entity_type = 'node';
      $base_field = 'nid';
    }
    $row->setSourceProperty('base_table', $entity_base_table);
    $row->setSourceProperty('base_field', $base_field);
    while ($result = $execute->fetchAssoc()) {
      $display_options = $result['display_options'];
      $id = $result['id'];
      $this->display = $id;
      $display_options = unserialize($display_options, ['allowed_classes' => FALSE]);
      if (isset($result['display_plugin'])) {
        if (!in_array($result['display_plugin'], $this->pluginList['display'])) {
          if (isset($display_plugin_map[$result['display_plugin']])) {
            $result['display_plugin'] = $display_plugin_map[$result['display_plugin']];
          }
          else {
            $result['display_plugin'] = 'default';
          }
        }
      }
      $display[$id]['display_plugin'] = $result['display_plugin'];
      $display[$id]['id'] = $result['id'];
      $display[$id]['display_title'] = $result['display_title'];
      $display[$id]['position'] = $result['position'];
      $display_options = $this->convertDisplayPlugins($display_options, $entity_type);
      $display_options = $this->convertFieldFormatters($display_options, $entity_type, $entity_base_table);
      $display_options = $this->convertDisplayOptions($display_options, $entity_type, $entity_base_table);
      unset($display_options['header']);
      unset($display_options['footer']);
      unset($display_options['empty']);
      $display[$id]['display_options'] = $display_options;
      $this->display = NULL;
    }
    $row->setSourceProperty('display', $display);
    $this->row = NULL;
    $this->view = NULL;
    return parent::prepareRow($row);
  }

  /**
   * ViewsMigration convertDisplayPlugins.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function convertFieldFormatters(array $display_options, string $entity_type, string $bt) {
    if (is_array($display_options['fields'])) {
      foreach ($display_options['fields'] as $key => $field) {
        if (!in_array($field['type'], $this->formatterList['all_formats'])) {
          if (isset($this->baseTableArray[$field['table']])) {
            $entity_detail = $this->baseTableArray[$field['table']];
            $temp_entity_base_table = $entity_detail['data_table'];
            $temp_entity_type = $entity_detail['entity_id'];
            $temp_base_field = $entity_detail['entity_keys']['id'];
            $config = 'field.storage.' . $entity_type . '.' . $field['field'];
          }
          else {
            $config = 'field.storage.' . $entity_type . '.' . $field['field'];
          }
          $field_config = \Drupal::config($config);
          if (!is_null($field_config)) {
            $type = $field_config->get('type');
            $settings = $field_config->get('settings');
            if (isset($this->formatterList['field_type'][$type])) {
              $display_options['fields'][$key]['type'] = $this->formatterList['field_type'][$type][0];
              $display_options['fields'][$key]['settings'] = $settings;
            }
          }
          else {
            unset($display_options['fields']['key']['type']);
          }
        }
      }
    }
    return $display_options;
  }

  /**
   * {@inheritdoc}
   */
  public function convertDisplayPlugins(array $display_options, $entity_type) {
    if (isset($display_options['query']['type'])) {
      if (!in_array($display_options['query']['type'], $this->pluginList['query'])) {
        $display_options['query'] = [
          'type' => 'views_query',
          'options' => [],
        ];
      }
    }
    if (isset($display_options['access']['type'])) {
      if (!in_array($display_options['access']['type'], $this->pluginList['access'])) {
        $display_options['access'] = [
          'type' => 'none',
        ];
      }
      switch ($display_options['access']['type']) {
        case 'role':
          $role_approved = [];
          if (!is_array($display_options['access']['role'])) {
            break;
          }
          foreach ($display_options['access']['role'] as $key => $value) {
            $role_approved[$this->userRoles[$key]] = $this->userRoles[$key];
          }
          unset($display_options['access']['role']);
          $display_options['access']['options']['role'] = $role_approved;
          break;

        case 'perm':
          $permissions_map = [
            'use PHP for block visibility' => 'use PHP for settings',
            'administer site-wide contact form' => 'administer contact forms',
            'post comments without approval' => 'skip comment approval',
            'edit own blog entries' => 'edit own blog content',
            'edit any blog entry' => 'edit any blog content',
            'delete own blog entries' => 'delete own blog content',
            'delete any blog entry' => 'delete any blog content',
            'create forum topics' => 'create forum content',
            'delete any forum topic' => 'delete any forum content',
            'delete own forum topics' => 'delete own forum content',
            'edit any forum topic' => 'edit any forum content',
            'edit own forum topics' => 'edit own forum content',
          ];
          $perm = $display_options['access']['perm'];
          $perm = $permissions[$perm] ?? $perm;
          if (is_null($perm)) {
            $perm = 'access content';
          }
          $display_options['access']['options']['perm'] = $perm;
          break;

        default:
          // code...
          break;
      }
    }
    if (isset($display_options['cache']['type'])) {
      if (!in_array($display_options['cache']['type'], $this->pluginList['cache'])) {
        $display_options['cache'] = [
          'type' => 'none',
        ];
      }
    }
    if (isset($display_options['exposed_form']['type'])) {
      if (!in_array($display_options['exposed_form']['type'], $this->pluginList['exposed_form'])) {
        $display_options['exposed_form'] = [
          'type' => 'basic',
        ];
      }
    }

    if (isset($display_options['pager']['type'])) {
      if (!in_array($display_options['pager']['type'], $this->pluginList['pager'])) {
        $display_options['pager'] = [
          'type' => 'none',
        ];
      }
    }
    if (isset($display_options['menu'])) {
      $menu_name_map = [
        'main-menu' => 'main',
        'management' => 'admin',
        'navigation' => 'tools',
        'user-menu' => 'account',
      ];
      if (isset($menu_name_map[$display_options['menu']['name']])) {
        $display_options['menu']['name'] = $menu_name_map[$display_options['menu']['name']];
      }
      $display_options['menu']['menu_name'] = $display_options['menu']['name'];
    }
    if (isset($display_options['row_plugin'])) {
      $row_plugin_map = [
        'node' => 'entity:node',
        'users' => 'entity:user',
        'taxonomy_term' => 'entity:taxonomy_term',
        'file_managed' => 'entity:file',
      ];
      if ($row_plugin_map[$display_options['row_plugin']]) {
        $display_options['row_plugin'] = $row_plugin_map[$display_options['row_plugin']];
      }
      $display_options['row']['type'] = $display_options['row_plugin'];
      if (!in_array($display_options['row_plugin'], $this->pluginList['row'])) {
        switch ($display_options['row_plugin']) {
          case 'node_rss':
            $rowOptions = $display_options['row_options'];
            $display_options['row'] = [
              'type' => $display_options['row_plugin'],
              'options' => [
                'relationship' => $rowOptions['relationship'],
                'view_mode' => $rowOptions['item_length'],
              ],
            ];
            unset($display_options['row_plugin']);
            unset($display_options['row_options']);
            break;

          default:
            $display_options['row_plugin'] = 'fields';
            break;
        }
      }
      if (isset($display_options['row_options'])) {
        $rowOptions = $display_options['row_options'];
        $display_options['row']['options'] = [
          'relationship' => $rowOptions['relationship'],
          'view_mode' => $rowOptions['item_length'],
        ];
      }
    }
    if (isset($display_options['style_plugin'])) {
      if (!in_array($display_options['style_plugin'], $this->pluginList['style'])) {
        $display_options['style_plugin'] = 'default';
      }
    }
    return $display_options;
  }

  /**
   * ViewsMigration convertDisplayOptions.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function convertDisplayOptions(array $display_options, string $entity_type, string $bt) {
    if (isset($display_options['relationships'])) {
      $display_options = $this->alterRelationshipsDisplayOptions($display_options, $entity_type, $bt);
    }
    if (isset($display_options['sorts'])) {
      $display_options = $this->alterDisplayOptions($display_options, 'sorts', $entity_type, $bt);
    }
    if (isset($display_options['filters'])) {
      $display_options = $this->alterFiltersDisplayOptions($display_options, 'filters', $entity_type, $bt);
      $display_options = $this->alterFilters($display_options, 'filters', $entity_type, $bt);
    }
    if (isset($display_options['arguments'])) {
      $display_options = $this->alterDisplayOptions($display_options, 'arguments', $entity_type, $bt);
      $display_options = $this->alterArgumentsDisplayOptions($display_options, 'arguments', $entity_type, $bt);
      $display_options = $this->alterArguments($display_options, 'arguments', $entity_type, $bt);
    }
    if (isset($display_options['argument_validator'])) {
      $display_options = $this->alterArgumentValidatorDisplayOptions($display_options, 'argument_validator', $entity_type, $bt);
    }
    if (isset($display_options['fields'])) {
      $display_options = $this->alterDisplayOptions($display_options, 'fields', $entity_type, $bt);
    }
    return $display_options;
  }

  /**
   * {@inheritdoc}
   */
  public function entityTableArray() {
    $this->entityTableArray = [];
    $this->entityTableArray['node'] = [
      'entity_id' => 'node',
      'data_table' => 'node_field_data',
      'entity_keys' => 'nid',
    ];
    $this->entityTableArray['term_data'] = [
      'entity_id' => 'taxonomy_term',
      'data_table' => 'taxonomy_term_field_data',
      'entity_keys' => 'tid',
    ];
    return $this->entityTableArray;
  }

  /**
   * ViewsMigration convertDisplayOptions.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function alterDisplayOptions(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $optionsMap = [
      'sorts' => 'sort',
      'filters' => 'filter',
      'arguments' => 'argument',
      'fields' => 'field',
    ];
    $doption = $optionsMap[$option];
    $tableMap = [
      'node' => 'node_field_data',
      'term_data' => 'taxonomy_term_field_data',
      'term_hierarchy' => 'taxonomy_term__parent',
      'term_node' => 'taxonomy_index',
    ];
    $fieldTableMap = [
      'node_revisions' => [
        'body' => [
          'table' => 'node__body',
          'field' => 'body',
          'type' => 'text_default',
        ],
        'teaser' => [
          'table' => 'node__body',
          'field' => 'body',
          'type' => 'text_summary_or_trimmed',
        ],
      ],
    ];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if ((isset($data['type']) && in_array($data['field'], $boolean_fields)) || in_array($data['type'], $types)) {
        if (!in_array($data['type'], $types)) {
          $data['type'] = 'yes-no';
        }
        $fields[$key]['type'] = 'boolean';
        $fields[$key]['settings']['format'] = $data['type'];
        $fields[$key]['settings']['format_custom_true'] = $data['type_custom_true'];
        $fields[$key]['settings']['format_custom_false'] = $data['type_custom_false'];
      }
      elseif (isset($data['alter']['text'])) {
        $data['alter']['text'] = str_replace("[", "{{", $data['alter']['text']);
        $fields[$key]['alter']['text'] = str_replace("]", "}}", $data['alter']['text']);
      }
      if (($data['field'] == 'area' && isset($data['content']) && ($option == 'header'|| $option == 'footer'))) {
        $content_value = str_replace("[", " {{", $data['content']);
        $content_value = str_replace("]", " }}", $content_value);
        $content_format = 'basic_html';
        $fields[$key]['content'] = [
          'value' => $content_value,
          'format' => $content_format,
        ];
      }
      if (($data['field'] == 'view' && isset($data['view_to_insert']) && ($option == 'header'|| $option == 'footer'))) {
        $fields[$key]['plugin_id'] = 'migration_view';
        $fields[$key]['field'] = 'migration_view';
      }
      if (isset($data['table'])) {
        if (isset($tableMap[$data['table']])) {
          $data['table'] = $tableMap[$data['table']];
        }
        $field = str_ireplace(['_fid', '_tid', '_uid', '_value'], '', $data['field']);
        $table = str_replace('node_data', 'node_', $data['table']);
        if (isset($fieldTableMap[$table][$field])) {
          $field = $fieldTableMap[$table][$field]['field'];
          $table = $fieldTableMap[$table][$field]['table'];
          $fields[$key]['type'] = $fieldTableMap[$table][$field]['type'];
        }
        if (isset($this->viewsData[$table])) {
          $entity_detail = $this->viewsData[$table];
          $fields[$key]['table'] = $table;

          if (isset($entity_detail[$field]['entity field'])) {
            $fields[$key]['field'] = $entity_detail[$field]['entity field'];
            $fields[$key]['entity_field'] = $entity_detail[$field]['entity field'];
          }
          elseif (isset($entity_detail[$field][$doption]['field_name'])) {
            $fields[$key]['field'] = $entity_detail[$field][$doption]['field_name'];
          }
          elseif (isset($entity_detail[$field][$doption]['real field'])) {
            $fields[$key]['field'] = $entity_detail[$field][$doption]['real field'];
          }

          if (isset($entity_detail[$field][$doption]['entity_type'])) {
            $fields[$key]['entity_type'] = $entity_detail[$field][$doption]['entity_type'];
          }
          elseif (isset($entity_detail[$field]['entity_type'])) {
            $fields[$key]['entity_type'] = $entity_detail[$field]['entity_type'];
          }

          if ($entity_detail[$field]['title']) {
            if (is_object($entity_detail[$field]['title'])) {
              $fields[$key]['label'] = $entity_detail[$field]['title']->__toString();
            }
            else {
              $fields[$key]['label'] = $entity_detail[$field]['title'];
            }
          }
          $fields[$key]['plugin_id'] = $entity_detail[$field][$doption]['id'];
        }
      }
      switch ($data['field']) {
        case 'views_bulk_operations':
          $fields[$key]['plugin_id'] = 'views_bulk_operations_bulk_form';
          $fields[$key]['table'] = 'views';
          $fields[$key]['field'] = 'views_bulk_operations_bulk_form';
          break;

        case 'operations':
          $fields[$key]['plugin_id'] = 'entity_operations';
          $fields[$key]['entity_type'] = $entity_type;
          $baseTable = \Drupal::entityTypeManager()->getStorage($entity_type)->getBaseTable();
          $fields[$key]['table'] = $baseTable;
          break;

        default:
          // code...
          break;
      }
      if (isset($data['field'])) {
        $types = [
          'view_node', 'edit_node', 'delete_node', 'cancel_node', 'view_user', 'view_comment', 'edit_comment', 'delete_comment', 'approve_comment', 'replyto_comment', 'comment', 'comment_count', 'last_comment_timestamp', 'last_comment_uid', 'last_comment_name',
        ];
        $table_map = [
          'views_entity_node' => 'node',
          'users' => 'users',
          'comment' => 'comment',
          'node_comment_statistics' => 'comment_entity_statistics',
        ];
        if (in_array($data['field'], $types)) {
          $fields[$key]['table'] = $table_map[$data['table']];
        }
        if (isset($this->viewsData[$entity_type][$data['field']])) {
          $fields[$key]['table'] = $entity_type;
          $fields[$key]['plugin_id'] = $this->viewsData[$entity_type][$data['field']][$option]['id'];
        }
      }
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * ViewsMigration alterFiltersDisplayOptions.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function alterFiltersDisplayOptions(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if (isset($data['table'])) {
        if (isset($this->baseTableArray[$data['table']])) {
          $entity_detail = $this->baseTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        elseif (isset($this->entityTableArray[$data['table']])) {
          $entity_detail = $this->entityTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        else {
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($fields[$key]['table'], 10);
          }
          else {
            $name = $fields[$key]['field'];
          }
          if (isset($fields[$key]['relationship'])) {
            $relationship_name = $fields[$key]['relationship'];
            if ($relationship_name == 'none' || $relationship_name == '' || is_null($relationship_name)) {
              $fields[$key] = $this->relationshipFieldChage($fields[$key], $entity_type, $fields, $key, $name);
            }
            else {
              $relationship = $views_relationships[$relationship_name];
              while (isset($relationship['relationship']) && $relationship['relationship'] != 'none' && $relationship['relationship'] != '' && !is_null($relationship['relationship'])) {
                $relationship_name = $relationship['relationship'];
                $relationship = $views_relationships[$relationship_name];
              }
              $fields[$key] = $this->relationshipFieldChage($relationship, $entity_type, $fields, $key, $name);
            }
          }
          else {
            $table = $entity_type . '_' . $name;
          }
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($fields[$key]['table'], 10);
            $fields[$key]['table'] = $table;
          }
          else {
            /* $fields[$key]['field'] = $bt; */
          }
        }
      }
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * {@inheritdoc}
   */
  public function alterRelationshipsDisplayOptions(array $display_options, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $relationships = $display_options['relationships'];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_relationships = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($relationships as $key => $data) {
      if (mb_substr($data['field'], -4) == '_nid' || mb_substr($data['field'], -4) == '_uid') {
        $data['field'] = mb_substr($data['field'], 0, -4) . '_target_id';
      }
      if ((isset($data['type']) && in_array($data['field'], $boolean_relationships)) || in_array($data['type'], $types)) {
        if (!in_array($data['type'], $types)) {
          $data['type'] = 'yes-no';
        }
        $relationships[$key]['type'] = 'boolean';
        $relationships[$key]['settings']['format'] = $data['type'];
        $relationships[$key]['settings']['format_custom_true'] = $data['type_custom_true'];
        $relationships[$key]['settings']['format_custom_false'] = $data['type_custom_false'];
      }
      if (isset($data['table'])) {
        $check_reverse = mb_substr($relationships[$key]['table'], 0, 8);
        if (isset($this->baseTableArray[$data['table']])) {
          $entity_detail = $this->baseTableArray[$data['table']];
          $relationships[$key]['table'] = $entity_detail['data_table'];
          $relationships[$key]['entity_type'] = $entity_detail['entity_id'];
        }
        else {
          $name = substr($relationships[$key]['table'], 11);
          if (isset($relationships[$key]['relationship'])) {
            $relationship_name = $relationships[$key]['relationship'];
            $relationship = $views_relationships[$relationship_name];
            if ($relationship['relationship'] == 'none') {
              $table = $entity_type . '__' . $name;
            }
            else {
              $table = $entity_type . '__' . $name;
            }
          }
          else {
            $table = $entity_type . '__' . $name;
          }
          $result = mb_substr($relationships[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($relationships[$key]['table'], 11);
            $relationships[$key]['table'] = $table;
            $relationships[$key]['field'] = $name;
          }
          else {
            /* $relationships[$key]['field'] = $bt; */
          }
        }
        if (mb_substr($key, 0, 8) == 'reverse_') {
          $field_name = str_replace('reverse_', '', $relationships[$key]['field']);
          $field_name = str_replace('_' . $entity_type, '', $field_name);
          $relationships[$key]['field'] = 'reverse__' . $entity_type . '__' . $field_name;
          $relationships[$key]['admin_label'] = $relationships[$key]['label'];
          unset($relationships[$key]['label']);
          unset($relationships[$key]['ui_name']);
          $relationships[$key]['plugin_id'] = 'entity_reverse';
        }
      }
    }
    $display_options['relationships'] = $relationships;
    return $display_options;
  }

  /**
   * ViewsMigration alterFiltersDisplayOptions.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function altersDisplayOptions(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if (isset($data['table'])) {
        if (isset($this->baseTableArray[$data['table']])) {
          $entity_detail = $this->baseTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        elseif (isset($this->entityTableArray[$data['table']])) {
          $entity_detail = $this->entityTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        else {
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($fields[$key]['table'], 10);
          }
          else {
            $name = $fields[$key]['field'];
          }
          if (isset($fields[$key]['relationship'])) {
            $relationship_name = $fields[$key]['relationship'];
            if ($relationship_name == 'none' || $relationship_name == '' || is_null($relationship_name)) {
              $fields[$key] = $this->relationshipFieldChage($fields[$key], $entity_type, $fields, $key, $name);
            }
            else {
              $relationship = $views_relationships[$relationship_name];
              while (isset($relationship['relationship']) && $relationship['relationship'] != 'none' && $relationship['relationship'] != '' && !is_null($relationship['relationship'])) {
                $relationship_name = $relationship['relationship'];
                $relationship = $views_relationships[$relationship_name];
              }
              $fields[$key] = $this->relationshipFieldChage($relationship, $entity_type, $fields, $key, $name);
            }
          }
          else {
            $table = $entity_type . '_' . $name;
          }
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($fields[$key]['table'], 10);
            $fields[$key]['table'] = $table;
          }
          else {
            /* $fields[$key]['field'] = $bt; */
          }
        }
      }
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * ViewsMigration alterFilters.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function alterFilters(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if (isset($data['expose'])) {
        $role_approved = [];
        if (isset($data['expose']['remember_roles'])) {
          foreach ($data['expose']['remember_roles'] as $rid => $role_data) {
            $role_approved[$this->userRoles[$rid]] = $this->userRoles[$rid];
          }
        }
        $data['expose']['remember_roles'] = $role_approved;
      }
      switch ($data['table']) {
        case 'users_roles':
          if (isset($data['value'])) {
            $role_approved = [];
            foreach ($data['value'] as $rid => $role_data) {
              $role_approved[$this->userRoles[$rid]] = $this->userRoles[$rid];
            }
            $data['value'] = $role_approved;
          }
          $data['plugin_id'] = 'user_roles';
          $data['entity_type'] = 'user';
          $data['entity_field'] = 'roles';
          $data['table'] = 'user__roles';
          $data['field'] = 'roles_target_id';
          break;

        case 'file_usage':
          $data['plugin_id'] = 'numeric';
          break;

        case 'views':
          if ($data['field'] == 'combine') {
            $data['plugin_id'] = 'combine';
          }
          break;

        default:
          // code...
          break;
      }
      if (isset($data['value']['type'])) {
        if ($data['value']['type'] == 'date') {
          $data['plugin_id'] = 'date';
        }
      }
      $table = $data['table'];
      $field = $data['field'];
      if (isset($this->viewsData[$table][$field]['filter']['id'])) {
        $data['plugin_id'] = $this->viewsData[$table][$field]['filter']['id'];
      }
      else {
        $data['plugin_id'] = 'bundle';
      }
      if (isset($this->viewsData[$table][$field]['filter']['id'])) {
        $data['entity_type'] = $this->views_data[$table][$field]['field']['entity_type'];
        $data['entity_field'] = $data['field'];
      }
      if (isset($this->viewsData[$entity_type][$field])) {
        $data['table'] = $entity_type;
        $data['plugin_id'] = $this->viewsData[$entity_type][$field][$option]['id'];
        if (isset($this->viewsData[$entity_type][$field]['filter']['id'])) {
          $data['entity_type'] = $this->views_data[$entity_type][$field]['field']['entity_type'];
          $data['entity_field'] = $data['field'];
        }
      }
      if (isset($data['vocabulary'])) {
        $data['plugin_id'] = 'taxonomy_index_tid';
        $data['vid'] = $data['vocabulary'];
        unset($data['vocabulary']);
      }
      $fields[$key] = $data;
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * ViewsMigration alterArgumentsDisplayOptions.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function alterArgumentsDisplayOptions(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $validate_map = [
      'term_data' => 'entity:taxonomy_term',
      'node' => 'entity:node',
      'users' => 'entity:user',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if (isset($data['table'])) {
        $type = $validate_map[$data['table']];
        if (in_array($type, $this->pluginList['argument_validator'])) {
          $data['validate']['type'] = $type;
        }
        if (isset($this->baseTableArray[$data['table']])) {
          $entity_detail = $this->baseTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        elseif (isset($this->entityTableArray[$data['table']])) {
          $entity_detail = $this->entityTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        else {
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data_') {
            $name = substr($fields[$key]['table'], 10);
          }
          else {
            $name = $fields[$key]['field'];
          }
          if (isset($fields[$key]['relationship'])) {
            $relationship_name = $fields[$key]['relationship'];
            if ($relationship_name == 'none' || $relationship_name == '' || is_null($relationship_name)) {
              $fields[$key] = $this->relationshipFieldChage($fields[$key], $entity_type, $fields, $key, $name);
            }
            else {
              $relationship = $views_relationships[$relationship_name];
              while (isset($relationship['relationship']) && $relationship['relationship'] != 'none' && $relationship['relationship'] != '' && !is_null($relationship['relationship'])) {
                $relationship_name = $relationship['relationship'];
                $relationship = $views_relationships[$relationship_name];
              }
              $fields[$key] = $this->relationshipFieldChage($relationship, $entity_type, $fields, $key, $name);
            }
          }
          else {
            $table = $entity_type . '_' . $name;
          }
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data_') {
            $name = substr($fields[$key]['table'], 10);
            $fields[$key]['table'] = $table;
          }
          else {
            /* $fields[$key]['field'] = $bt; */
          }
        }
      }
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * ViewsMigration alterArgumentValidatorDisplayOptions.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function alterArgumentValidatorDisplayOptions(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $validate_map = [
      'term_data' => 'entity:taxonomy_term',
      'node' => 'entity:node',
      'users' => 'entity:user',
    ];
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if (isset($data['table'])) {
        $type = $validate_map[$data['table']];
        if (in_array($type, $this->pluginList['argument_validator'])) {
          $data['validate']['type'] = $type;
          $data['validate_type'] = $type;
        }
        if (isset($this->baseTableArray[$data['table']])) {
          $entity_detail = $this->baseTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        elseif (isset($this->entityTableArray[$data['table']])) {
          $entity_detail = $this->entityTableArray[$data['table']];
          $fields[$key]['table'] = $entity_detail['data_table'];
        }
        else {
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($fields[$key]['table'], 10);
          }
          else {
            $name = $fields[$key]['field'];
          }
          if (isset($fields[$key]['relationship'])) {
            $relationship_name = $fields[$key]['relationship'];
            if ($relationship_name == 'none' || $relationship_name == '' || is_null($relationship_name)) {
              $fields[$key] = $this->relationshipFieldChage($fields[$key], $entity_type, $fields, $key, $name);
            }
            else {
              $relationship = $views_relationships[$relationship_name];
              while (isset($relationship['relationship']) && $relationship['relationship'] != 'none' && $relationship['relationship'] != '' && !is_null($relationship['relationship'])) {
                $relationship_name = $relationship['relationship'];
                $relationship = $views_relationships[$relationship_name];
              }
              $fields[$key] = $this->relationshipFieldChage($relationship, $entity_type, $fields, $key, $name);
            }
          }
          else {
            $table = $entity_type . '_' . $name;
          }
          $result = mb_substr($fields[$key]['table'], 0, 10);
          if ($result == 'node_data') {
            $name = substr($fields[$key]['table'], 10);
            $fields[$key]['table'] = $table;
          }
          else {
            /* $fields[$key]['field'] = $bt; */
          }
        }
      }
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * ViewsMigration alterArguments.
   *
   * @param array $display_options
   *   Views dispaly options.
   * @param string $option
   *   View section option.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $bt
   *   Views base table.
   */
  public function alterArguments(array $display_options, string $option, string $entity_type, string $bt) {
    $views_relationships = $this->viewsRelationshipData;
    $db_schema = Database::getConnection()->schema();
    $fields = $display_options[$option];
    $types = [
      'yes-no', 'default', 'true-false', 'on-off', 'enabled-disabled',
      'boolean', 'unicode-yes-no', 'custom',
    ];
    $boolean_fields = [
      'status',
      'sticky',
      'promote',
    ];
    foreach ($fields as $key => $data) {
      if (isset($data['exception'])) {
        if (isset($data['expose']['title_enable']) && $data['expose']['title_enable'] = 1) {
          $data['expose']['title_enable'] = ($data['expose']['title_enable'] == 1) ? TRUE : FALSE;
        }
      }
      if (isset($data['title_enable'])) {
        $data['title_enable'] = ($data['title_enable'] == 1) ? TRUE : FALSE;
      }
      if (isset($data['specify_validation'])) {
        $data['specify_validation'] = ($data['specify_validation'] == 1) ? TRUE : FALSE;
      }
      switch ($data['table']) {
        case 'users_roles':
          if (isset($data['value'])) {
            $role_approved = [];
            foreach ($data['value'] as $rid => $role_data) {
              $role_approved[$this->userRoles[$rid]] = $this->userRoles[$rid];
            }
            $data['value'] = $role_approved;
          }
          $data['plugin_id'] = 'user_roles';
          $data['entity_type'] = 'user';
          $data['entity_field'] = 'roles';
          $data['table'] = 'user__roles';
          $data['field'] = 'roles_target_id';
          break;

        case 'views':
          if ($data['field'] == 'combine') {
            $data['plugin_id'] = 'combine';
          }
          break;

        default:
          // code...
          break;
      }

      $table = $data['table'];
      $field_name = $data['field'];
      $entity_id_check = mb_substr($data['field'], ($name_len - 4), 4);
      $field_name = $fields[$key]['field'];
      if ($entity_id_check == '_tid' || $entity_id_check == '_uid' || $entity_id_check == '_nid') {
        $field_name = mb_substr($data['field'], 0, ($name_len - 4));
        $data['field'] = $field_name . '_target_id';
      }
      $value_check = mb_substr($data['field'], ($name_len - 6), 6);
      if ($value_check == '_value') {
        $field_name = mb_substr($data['field'], 0, ($name_len - 6));
      }
      if (isset($this->viewsData[$table][$field_name]['argument']['id'])) {
        $data['plugin_id'] = $this->viewsData[$table][$field_name]['argument']['id'];
      }
      if (isset($data['validate_type'])) {
        $data['validate'] = [
          'type' => $data['validate_type'],
          'fail' => $data['validate_fail'],
        ];
      }
      $fields[$key] = $data;
    }
    $display_options[$option] = $fields;
    return $display_options;
  }

  /**
   * ViewsMigration relationshipFieldChage.
   *
   * @param array $relationship
   *   Views dispaly options.
   * @param string $entity_type
   *   Views base entity type.
   * @param string $fields
   *   View fields data.
   * @param string $key
   *   Views base table.
   * @param string $name
   *   Views field name.
   */
  public function relationshipFieldChage(array $relationship, $entity_type, $fields, $key, $name) {
    $dont_changes = ['users_roles', 'file_usage', 'views'];
    if (in_array($fields[$key]['table'], $dont_changes)) {
      return $fields[$key];
    }

    $relation_entity_type = $entity_type;
    if (isset($this->entityTableArray[$relationship['field']])) {
      $entity_detail = $this->entityTableArray[$relationship['field']];
      $relation_entity_type = $entity_detail['entity_id'];
    }
    else {
      $name_len = strlen($fields[$key]['field']);
      $entity_id_check = mb_substr($fields[$key]['field'], ($name_len - 4), 4);
      $field_name = $fields[$key]['field'];
      if ($entity_id_check == '_tid' || $entity_id_check == '_uid' || $entity_id_check == '_nid') {
        $field_name = mb_substr($fields[$key]['field'], 0, ($name_len - 4));
        $fields[$key]['field'] = $field_name . '_target_id';
      }
      $value_check = mb_substr($fields[$key]['field'], ($name_len - 6), 6);
      if ($value_check == '_value') {
        $field_name = mb_substr($fields[$key]['field'], 0, ($name_len - 6));
      }
      $config = 'field.storage.' . $relation_entity_type . '.' . $relationship['field'];
      $field_config = \Drupal::config($config);
      if (!is_null($field_config)) {
        $type = $field_config->get('type');
        $settings = $field_config->get('settings');
        if (isset($settings['target_type'])) {
          $relation_entity_type = $settings['target_type'];
          $fields[$key]['field'] = str_replace($relation_entity_type . '__', '', $fields[$key]['field']);
        }
      }
    }

    $field_name = str_replace('node_data_', '', $relationship['table']);
    $config = 'field.storage.' . $relation_entity_type . '.' . $field_name;
    $field_config = \Drupal::config($config);
    if (!is_null($field_config)) {
      $type = $field_config->get('type');
      $settings = $field_config->get('settings');
      if (isset($settings['target_type'])) {
        if ($settings['target_type'] == 'taxonomy_term') {
          $table = $relation_entity_type . '_' . $name;
        }
        else {
          $table = $settings['target_type'] . '_' . $name;
        }
      }
      else {
        $table = $relation_entity_type . '_' . $name;
      }
    }
    else {
      unset($display_options['fields']['key']['type']);
    }
    $fields[$key]['table'] = $table;
    return $fields[$key];
  }

}
