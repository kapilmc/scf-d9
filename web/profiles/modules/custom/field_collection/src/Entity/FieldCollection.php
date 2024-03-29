<?php

namespace Drupal\field_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\field_collection\FieldCollectionInterface;

/**
 * Defines the Field collection configuration entity.
 *
 * @ConfigEntityType(
 *   id = "field_collection",
 *   label = @Translation("Field collection"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigEntityStorage",
 *     "access" = "Drupal\field_collection\FieldCollectionAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\field_collection\FieldCollectionForm",
 *       "edit" = "Drupal\field_collection\FieldCollectionForm",
 *       "delete" = "Drupal\field_collection\Form\FieldCollectionDeleteConfirm"
 *     },
 *     "list_builder" = "Drupal\field_collection\FieldCollectionListBuilder",
 *   },
 *   admin_permission = "administer content types",
 *   config_prefix = "field_collection",
 *   bundle_of = "field_collection_item",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "enabled",
 *     "first_field",
 *     "second_field",
 *     "bundles",
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/field_collections/manage/{field_collection}"
 *   }
 * )
 */
class FieldCollection extends ConfigEntityBundleBase implements FieldCollectionInterface {

  /**
   * The machine name of this field collection.
   *
   * @var string
   */
  protected $id;

  /**
   * The UUID of the field collection type.
   *
   * @var string
   */
  protected $uuid;

  /**
   * The human-readable name of the field collection.
   *
   * @var string
   */
  protected $label;

  /**
   * TODO: Figure out if this is really needed (it may not be defined by entity classes).
   */
  protected $entityType;

  public function __construct(array $values = [], $entity_type = 'field_collection') {
    parent::__construct($values, $entity_type);
    $this->entityType = 'field_collection';
  }

}
