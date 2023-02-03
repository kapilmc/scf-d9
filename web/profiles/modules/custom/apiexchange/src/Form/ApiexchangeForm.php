<?php
/**
 * @file
 * Contains \Drupal\apiexchange\Form\ApiexchangeForm.
 * @param string apiexchange
 */
namespace Drupal\apiexchange\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;

class ApiexchangeForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'apiexchnageid';
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['apiexchange_url'] = array(
        '#type' => 'textfield',
        '#title' => t('API Url'),
        '#required' => true,
        '#maxlength' => 255,
        //  '#default_value' => variable_get('apiexchange_url', ''),
        );
      
        $form['apiexchange_key'] = array(
        '#type' => 'textfield',
        '#title' => t('API KEY'),
        '#required' => true,
        '#maxlength' => 255,
        // '#default_value' => variable_get('apiexchange_key', ''),
        );

        $form['submit'] = array(
          '#type' => 'submit',
          '#value' => t('Save API')
          );

        return $form;

    }

    // public function validateForm(array $form, FormStateInterface $form_state) {

    // }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $data = [
          'apiexchange_url'=> $form_state->getValue('apiexchange_url'),
          'apiexchange_key'=> $form_state->getValue('apiexchange_key'),
        ];
        if (isset($_GET['id'])) {
            // update data in database
            \Drupal::database()->update('apiexchange')->fields($data)->condition('id', $_GET['id'])->execute();

        } else {
            // Insert data to database.
            \Drupal::database()->insert('apiexchange')->fields($data)->execute();

        }
     
        // Show message and redirect to list page.
        \Drupal::messenger()->addStatus($this->t(' Your data Successfully saved'));
        // $url = new Url('demo.listing_form');
        // $response = new RedirectResponse($url->toString());
        // $response->send();

    }
}