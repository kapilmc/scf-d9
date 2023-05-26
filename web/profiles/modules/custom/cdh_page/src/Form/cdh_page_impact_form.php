<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\cdh_page_impact_form.
 */
namespace Drupal\cdh_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for cdh_page_impact_form Config Form.
 */
class cdh_page_impact_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'cdh_page_impact_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_impact.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cdh_page_impact.settings');
       
          $form['cdh_page_impact_title'] = array(
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#default_value' => $config->get('cdh_page_impact_title'),
          );
        
          $form['cdh_page_impact_file'] = array(
            '#title' => t('Upload Image'),
            '#type' => 'managed_file',
            '#required' => true,
            '#default_value' => $config->get('cdh_page_impact_file'),
            '#upload_location' => 'public://cdh-page/',
            "#upload_validators"  => array("file_validate_extensions" => array("jpg jpeg png")),
            '#description' => '',
          );
        
          $form['actions']['save'] = array(
            '#type' => 'submit',
            '#value' => t('Save Changes'),
            // '#submit' => array('_cdh_page_impact_form'),
          );

          return $form;

    }
    

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('cdh_page_impact.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }







        // if(isset($form_state['default_press_release_image'])) {
        //   // Set file status to permanent.
        //   $image = $form_state->getValue('cdh_page_impact_file');
        //   $file = File::load($image[0]);
        //   $file->setPermanent();
        //   $file->save();
        //   // Add to file usage calculation.
        //   \Drupal::service('file.usage')->add;
        // }
    
        // $this->config('cdh_page_impact.settings')
        //   ->set('cdh_page_impact_file', $form_state->getValue('cdh_page_impact_file'))
        //   ->set('cdh_page_impact_title', $form_state->getValue('cdh_page_impact_title'))
        //   ->save();
    
        // parent::submitForm($form, $form_state);


    }
}
