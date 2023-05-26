# Field Collection To Paragraphs migration

Provides a way to migrate Drupal 8+ sites using Field Collection fields to equivalent Paragraphs fields.

A database backup is always recommended before running any migrations.

## Disclaimers

1. The migration will not delete the existing field_collection fields from their attached entities. This will need to be done after confirming the data has converted correctly.
2. This process creates new fields on the base entities - the existing fields are not converted in-place. As a result all output will need to be updated to use the new fields.
3. Only nodes and taxonomy terms are supported as parent entities, additional plugins will need to be written for any other entity types that might exist.

## Dependencies

The following modules are required in order for this to work:

* [Field Collections](https://www.drupal.org/project/field_collection) 8.x-1.x or 8.x-3.x
* [Migrate Plus](https://www.drupal.org/project/migrate_plus) 8.x-4.2+ or a 8.x-5.x release.
* [Migrate Tools](https://www.drupal.org/project/migrate_tools) 8.x-4.5+ or a 8.x-5.x release
* [Paragraphs](https://www.drupal.org/project/paragraphs)

## Standard workflow

The expected workflow is:

* Install the module.
* Export the configuration (`drush config:export`).
* Modify the configuration files as necessary, primarily the `migrate_plus.migration_group.field_collection_to_paragraphs.yml` file.
* Reimport the changes (`drush config:import`).
* Run the migration (see below).

## Configuration

Global configuration options are available inside the `migrate_plus.migration_group.field_collection_to_paragraphs` config under the `shared_configuration/source` property.

- `field_collection_types`: An array of Field Collection types to migrate to Paragraphs. By default all Field Collection fields will be converted, but it may be limited to specific fields by modifying this setting.
- `field_collection_type_mapping`: A mapping for Field Collection bundle names to Paragraph ones.
- `field_collection_field_name_mapping` - A mapping for Field Collection field names to their destination names when attached to a parent entity.
- `field_collection_field_name_suffix` - By default all of the converted fields on the parent entities will have the suffix `_paragraph` added. This may be changed by modifying this setting.

## Running the migration

You'll want to run the `field_collection_to_paragraph_parent_node` and `field_collection_to_paragraph_parent_taxonomy_term` migrations as normal.

This can be done from the migration admin menu (`/admin/structure/migrate/manage/field_collection_to_paragraphs/migrations`) or using Drush.

### Using Drush

Simply migrate all your existing field collections by running:

  drush migrate-import field_collection_to_paragraph_parent_node,field_collection_to_paragraph_parent_taxonomy_term --execute-dependencies

Or run them step by step using the following script:

```bash
# Create the paragraph types.
drush migrate-import field_collection_to_paragraph_type

# Create the paragraph item fields and display settings.
drush migrate-import field_collection_to_paragraph_field_storage
drush migrate-import field_collection_to_paragraph_field_instance
drush migrate-import field_collection_to_paragraph_field_entity_view
drush migrate-import field_collection_to_paragraph_field_entity_form_display

# Create relevant fields on the parent node/taxonomy terms.
drush migrate-import field_collection_to_paragraph_parent_field_storage
drush migrate-import field_collection_to_paragraph_parent_field_instance

# Create the equivalent paragraph items.
drush migrate-import field_collection_to_paragraph_item

# Create relevant display settings on the parent node/taxonomy terms.
drush migrate-import field_collection_to_paragraph_parent_field_entity_view
drush migrate-import field_collection_to_paragraph_parent_field_entity_form_display

# Attach the paragraphs to the parent node/taxonomy terms.
drush migrate-import field_collection_to_paragraph_parent_node
drush migrate-import field_collection_to_paragraph_parent_taxonomy_term
```

## Notes

### migrate:status oddities

When the module is first installed the `field_collection_to_paragraph_parent_*` migrations will show zero records. However, when those migrations actually run they will show valid records. This is because the parent entity migrations run a query that depends upon the `field_collection_to_paragraph_parent_field_instance` migration having already executed.

## Credits / contact

Written and maintained by [codebymikey](https://www.drupal.org/u/codebymikey). Additional maintenance by [Damien
McKenna](https://www.drupal.org/u/damienmckenna).

The best way to contact the authors is to submit an issue, be it a support
request, a feature request or a bug report, in the [project issue
queue](https://www.drupal.org/project/issues/field_collection_to_paragraphs).
