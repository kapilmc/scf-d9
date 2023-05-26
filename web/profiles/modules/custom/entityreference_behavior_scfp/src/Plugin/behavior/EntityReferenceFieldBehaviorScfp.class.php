<?php

class EntityReferenceFieldBehaviorScfp extends EntityReference_BehaviorHandler_Abstract {
  public function is_empty_alter(&$empty, $item, $field) {
    $empty = false;
  }

  /**
   * Generate a settings form for this handler.
   */
  public function settingsForm($field, $instance) {
    $form['scfp_field'] = array(
      '#type' => 'checkbox',
      '#title' => t('Field behavior setting'),
    );
    return $form;
  }
}
