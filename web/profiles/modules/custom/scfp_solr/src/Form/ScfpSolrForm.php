<?php
/**
 * @file
 * Contains \Drupal\scfp_solr\Form\ScfpSolrForm.
 */
namespace Drupal\scfp_solr\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for ScfpSolrForm Config Form.
 */
class ScfpSolrForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'ScfpSolrForm';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['scfpsolr.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('scfpsolr.settings');
        $form['scfp_solr_host'] = array(
          '#type' => 'textfield',
          '#title' => t('Host'),
          '#help' => t('The host name or IP of your Solr server, e.g. localhost or www.example.com.'),
          // '#default_value' => variable_get('scfp_solr_host', 'localhost'),
          '#default_value' => $config->get('scfp_solr_host'),
          '#reqiured' => true,
          );
      
          $form['scfp_solr_port'] = array(
          '#type' => 'textfield',
          '#title' => t('Solr port'),
          '#help' => t('The Jetty example server is at port 8983, while Tomcat uses 8080 by default.'),
          // '#default_value' => variable_get('scfp_solr_port', '8983'),
          '#default_value' => $config->get('scfp_solr_port'),
          '#reqiured' => true,
          );
      
          $form['scfp_solr_path'] = array(
          '#type' => 'textfield',
          '#title' => t('Solr path'),
          '#help' => t('The path that identifies the Solr instance to use on the server.'),
          // '#default_value' => variable_get('scfp_solr_path', '/solr/'),
          '#default_value' => $config->get('scfp_solr_path'),
          '#reqiured' => true,
          );
      
          $form['scfp_solr_core'] = array(
          '#type' => 'textfield',
          '#title' => t('Solr core'),
          '#help' => t('The path that identifies the Solr instance to use on the server.'),
          // '#default_value' => variable_get('scfp_solr_core', 'collection1'),
          '#default_value' => $config->get('scfp_solr_core'),
          '#reqiured' => true,
          );
          
          $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save configuration'),
          );

          return $form;

    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('scfpsolr.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }
    }

}