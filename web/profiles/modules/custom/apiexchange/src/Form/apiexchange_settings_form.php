<?php

namespace Drupal\apiexchange\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class apiexchange_settings_form extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'apiexchange_settings_form.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'apiexchange_settings_form';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['apiexchange_url'] = array(
        '#type' => 'textfield',
        '#title' => t('API Url'),
        '#required' => TRUE,
        '#maxlength' => 255,
       '#default_value' => $config->get('apiexchange_url', ''),
      );
      
     $form['apiexchange_key'] = array(
      '#type' => 'textfield',
      '#title' => t('API KEY'),
      '#required' => TRUE,
      '#maxlength' => 255,
      '#default_value' => $config->get('apiexchange_key', ''),
     );








    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('apiexchange_url', $form_state->getValue('apiexchange_url'))
      // You can set multiple configurations at once by making
      // multiple calls to set().
      ->set('apiexchange_key', $form_state->getValue('apiexchange_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}