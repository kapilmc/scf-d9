<?php
/**
 * @file
 * Contains \Drupal\csvImporter\Form\CsvImporterEconomicLinkfForm.
 */
namespace Drupal\csvImporter\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;

class csvImporter_economic_link_form extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return ' csvImporter_economic_link_form';
  }


    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['csvImporter_economic_link.settings'];
    }
  
    /**
     * {@inheritdoc}
     */





  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('csvImporter_economic_link.settings');

    $form['csvimporter-economic-analytic-link'] = array(
        '#type' => 'textarea',
        '#title' => 'Enter the link to Economic Analytis Platform',
        '#rows' => 2,
        '#resizable' => TRUE,
        // '#value' => $config->get('csvimporter-economic-analytic-link'),
        '#default_value' => $config->get('csvimporter-economic-analytic-link'),
      );
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save Platform link')
      );
  
      return $form;
  
  }






  public function validateForm(array &$form, FormStateInterface $form_state) {


  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

  //   foreach ($form_state->getValues() as $key => $value) {
  //     $this->config('csvImporter_economic_link.settings')
  //         ->set($key, $value)->save();

  //     // parent::submitForm($form, $form_state);
  // }
  
    $this->config('csvImporter_economic_link.settings')  
      ->set('csvimporter-economic-analytic-link', $form_state->getValue('csvimporter-economic-analytic-link'))  
      ->save();



  }

}