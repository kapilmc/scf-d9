<?php
/**
 * @file
 * Contains \Drupal\apiexchange\Form\ApiexchangeEntityForm.
 */
namespace Drupal\apiexchange\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;

class ApiexchangeEntityForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'apiexchnageentityid';
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['new'] = array(
        '#type' => 'hidden',
        '#value' => empty($api) ? 1 : 0,
        );
        $form['name'] = array(
        '#type' => 'textfield',
        // '#default_value' => empty($api) ? '' : $api['name'],
        '#default_value' => (isset($data['name'])) ? $data['name'] : '',
        '#title' => t('API'),
        '#required' => true,
        '#maxlength' => 50,
        '#description' => t('Name of API'),
        );
        // if(empty($api)) {
        //     $form['id'] = array(
        //        '#type' => 'machine_name',
        //        '#default_value' => empty($api) ? '' : $api['id'],
        //        '#maxlength' => 50,
        //        '#machine_name' => array('exists' => 'apiexchange_entity_exist',
        //          'source' => array('name'),
        //          'label' => t('machine_name'),
        //          'replace_pattern' => '[^a-z0-9-]+',
        //          'replace' => '-',
        //         ),
        //        '#required' => TRUE,
        //        '#title' => t('API machine name')
        //     );
        //    }
        //    else {
        //     $form['id'] = array(
        //      '#type' => 'hidden',
        //      '#value' => $api['id'],
        //    );
        //    }
   
        $form['type'] = array(
        '#type' => 'select',
        '#options' => array('GET' => 'GET', 'POST' => 'POST'),
        '#title' => t('API Request Type'),
        '#required' => true,
        // '#default_value' => empty($api) ? 'GET' : $api['type'],
        '#default_value' => (isset($data['type'])) ? $data['type'] : '',
        '#description' => t('Type of API request (POST or Post)'),
        );
        $form['return_type'] = array(
        '#type' => 'select',
        '#options' => array('xml' => 'XML', 'json' => 'JSON'),
        '#title' => t('API Response Type'),
        '#required' => true,
        // '#default_value' => empty($api) ? 'GET' : $api['return_type'],
        '#description' => t('Type of API response (POST or Post)'),
        );
    
        $form['url'] = array(
        '#type' => 'textfield',
        // '#default_value' => empty($api) ? '' : $api['url'],
        '#title' => t('API URL'),
        '#required' => true,
        '#description' => t('URL of API e.g. /v1/mckinsey_insights/popular_articles'),
        );
        $form['custom_header'] = array(
        '#type' => 'textarea',
        // '#default_value' => empty($api) ? '' : $api['custom_header'],
        '#title' => t('Custom Header'),
        '#required' => false,
        '#description' => t('Custom header of API e.g. Content-Type:application/json. Enter one header per line.'),
        );
        $form['detail'] = array(
        '#type' => 'textarea',
        // '#default_value' => empty($api) ? '' : $api['detail'],
        '#title' => t('API Description'),
        '#required' => true,
        '#description' => t('Short description of API.'),
        );
        $form['debug'] = array(
        '#type' => 'checkbox',
        '#title' => t('Debug Mode'),
        // '#default_value' => empty($api) ? 0 : $api['debug'],
        );
        // $form['uid'] = array(
        //   '#type' => 'textarea',
        //   // '#default_value' => empty($api) ? '' : $api['detail'],
        //   '#title' => t('uid'),
        //   '#required' => true,
        //   // '#description' => t('Short description of API.'),
        //   );
        // $form['timestamp'] = array(
        //   '#type' => 'textarea',
        //   // '#default_value' => empty($api) ? '' : $api['detail'],
        //   '#title' => t('timestamp'),
        //   '#required' => true,
        //   // '#description' => t('Short description of API.'),
        //   );
        // $form['updated'] = array(
        //   '#type' => 'textarea',
        //   // '#default_value' => empty($api) ? '' : $api['detail'],
        //   '#title' => t('updated'),
        //   '#required' => true,
        //   // '#description' => t('Short description of API.'),
        //   );  
    
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
            'id'=> $form_state->getValue('id'),

        'name'=> $form_state->getValue('name'),
        'type'=> $form_state->getValue('type'),
        'return_type'=> $form_state->getValue('return_type'),
        'url'=> $form_state->getValue('url'),
        'custom_header'=> $form_state->getValue('custom_header'),
        'detail'=> $form_state->getValue('detail'),
        'debug'=> $form_state->getValue('debug'),
        // 'uid'=> $form_state->getValue('uid'),
        // 'timestamp'=> $form_state->getValue('timestamp'),
        // 'updated'=> $form_state->getValue('updated'),
        ];
        
        // dd($data);
        if (isset($_GET['id'])) {
            // update data in database
            \Drupal::database()->update('apiexchange')->fields($data)->condition('id', $_GET['id'])->execute();

        } else {
            // Insert data to database.
            $result = \Drupal::database()->insert('apiexchange')->fields($data)->execute();
            // dd($result);
        }

        // Show message and redirect to list page.
        \Drupal::messenger()->addStatus($this->t(' Your data is Successfully saved'));
        // $url = new Url('demo.listing_form');
        // $response = new RedirectResponse($url->toString());
        // $response->send();

    }
}

