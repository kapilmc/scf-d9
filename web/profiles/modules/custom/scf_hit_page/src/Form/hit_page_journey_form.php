<?php
/**
 * Contains \Drupal\scf_hit_page\Form\hit_page_journey_form.
 * 
 * @param string scf_hit_page
 */
namespace Drupal\scf_hit_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;

class hit_page_journey_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'hit_page_journey_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['hit_page_journey.settings'];
    }
  
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $config = $this->config('hit_page_journey.settings');
       
        $form['hit_page_journey_title'] = array(
          '#type' => 'textfield',
          '#title' => t('Title'),
          '#default_value' => $config->get('hit_page_journey_title'),
        );
      
        $form['hit_page_journey_url'] = array(
          '#type' => 'textfield',
          '#title' => t('URL'),
          '#default_value' => $config->get('hit_page_journey_url'),
        );
      
        $form['hit_page_journey_file'] = array(
          '#title' => t('Upload Image'),
          '#type' => 'managed_file',
          '#required' => TRUE,
          '#default_value' => $config->get('hit_page_journey_file'),
          '#upload_location' => 'public://hit-page/',
          "#upload_validators"  => array("file_validate_extensions" => array("jpg jpeg png")),
          '#description' => '',
        );
      
        $form['actions']['save'] = array(
          '#type' => 'submit',
          '#value' => t('Save Changes'),
        //   '#submit' => array('_hit_page_journey_form'),
        );
        return $form;








      }














        


    // public function validateForm(array $form, FormStateInterface $form_state)
    // {

    // }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('hit_page_journey.settings')
                ->set($key, $value)->save();
    
            // parent::submitForm($form, $form_state);
            \Drupal::messenger()->addMessage('Form has been saved successfully');
        }
    }
}