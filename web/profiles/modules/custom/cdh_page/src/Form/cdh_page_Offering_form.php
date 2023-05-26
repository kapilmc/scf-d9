<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\cdh_page_Offering_form.
 */
namespace Drupal\cdh_page\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class for cdh_page_Offering_form Config Form.
 */
class cdh_page_Offering_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'cdh_page_Offering_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_Offering.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cdh_page_Offering.settings');


        $cdh_offering_descrition_1 = $config->get('cdh_offering_descrition_1', '');
        $cdh_offering_descrition_2 = $config->get('cdh_offering_descrition_2', '');
        $form['cdh_page_offering_title'] = array(
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#default_value' => $config->get('cdh_page_offering_title'),
          );
          
       

              $form['cdh_offering_title_1'] = array(
                     '#type' => 'textfield',
                    '#default_value' =>  $config->get('cdh_offering_title_1'),
                    '#size' => 60,
                    '#maxlength' => 255,
                    '#required' => false,
              );

              $form['cdh_offering_descrition_1'] = array(
                    '#type' => 'text_format',
                    '#format' => isset($cdh_offering_descrition_1['format']) ? $cdh_offering_descrition_1['format'] : 'filtered_html',
                    // '#title' => t('Description'),
                    '#default_value' => isset($cdh_offering_descrition_1['value']) ? $cdh_offering_descrition_1['value'] : '',
                    '#rows' => 6,
 
          );
          $form['cdh_offering_title_2'] = array(
             '#type' => 'textfield',
            '#default_value' =>  $config->get('cdh_offering_title_2'),
            '#size' => 60,
            '#maxlength' => 255,
            '#required' => false,
                );

          $form['cdh_offering_descrition_2'] = array(
           '#type' => 'text_format',
          '#format' => isset($cdh_offering_descrition_2['format']) ? $cdh_offering_descrition_2['format'] : 'filtered_html',
          // '#title' => t('Description'),
          '#default_value' => isset($cdh_offering_descrition_2['value']) ? $cdh_offering_descrition_2['value'] : '',
          '#rows' => 6,

           );

           $form['cdh_page_offering_file'] = array(
            '#title' => t('Upload Image'),
            '#type' => 'managed_file',
            // '#required' => TRUE,
            '#default_value' => $config->get('cdh_page_offering_file'),
            '#upload_location' => 'public://cdh-page/',
            "#upload_validators"  => array("file_validate_extensions" => array("jpg jpeg png")),
            '#description' => '',
          );

          // $form['items']['#tree'] = true;
          // $form['items'][0] = [
          //   'cdh_offering_title_1' => [
          //     '#type' => 'textfield',
          //     '#default_value' =>  $config->get('cdh_offering_title_1'),
          //     '#size' => 60,
          //     '#maxlength' => 255,
          //     '#required' => false,
          //   ],
          //   'cdh_offering_descrition_1' => [
          //     '#type' => 'text_format',
          //     '#format' => isset($cdh_offering_descrition_1['format']) ? $cdh_offering_descrition_1['format'] : 'filtered_html',
          //     // '#title' => t('Description'),
          //     '#default_value' => isset($cdh_offering_descrition_1['value']) ? $cdh_offering_descrition_1['value'] : '',
          //     '#rows' => 6,
          //   ],
          //   'cdh_offering_title_2' => [
          //     '#type' => 'textfield',
          //     '#default_value' => $config->get('cdh_offering_title_2'),
          //     '#size' => 60,
          //     '#maxlength' => 255,
          //     '#required' => false,
          //   ],
          //   'cdh_offering_descrition_2' => [
          //     '#type' => 'text_format',
          //     '#format' => isset($cdh_offering_descrition_2['format']) ? $cdh_offering_descrition_2['format'] : 'filtered_html',
          //   //  '#title' => t('Description'),
          //     '#default_value' => isset($cdh_offering_descrition_2['value']) ? $cdh_offering_descrition_2['value'] : '',
          //     '#rows' => 6,
          //   ],
           
          // ];
          $form['actions']['save'] = array(
            '#type' => 'submit',
            '#value' => t('Save Changes'),
            // '#submit' => array('_cdh_page_Offering_form'),
          );


          return $form;

    }
    

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('cdh_page_Offering.settings')
                ->set($key, $value)->save();
                \Drupal::messenger()->addMessage(t('Form has been saved successfully'));
            // parent::submitForm($form, $form_state);
        }


    // Retrieve the configuration.
    // $this->config('cdh_page_Offering.settings')
     
    //   ->set('cdh_page_offering_title', $form_state->getValue('cdh_page_offering_title'))
    //   ->set('cdh_page_offering_file', $form_state->getValue('cdh_page_offering_file'))
      
    //   ->set('cdh_offering_title_1', $form_state->getValue('cdh_offering_title_1'))
    //   ->set('cdh_offering_title_2', $form_state->getValue('cdh_offering_title_2'))
    //   ->set('cdh_offering_descrition_1', $form_state->getValue('cdh_offering_descrition_1'))
    //   ->set('cdh_offering_descrition_2', $form_state->getValue('cdh_offering_descrition_2'))
    //   ->set('cdh_page_offering_file', $form_state->getValue('cdh_page_offering_file'))
    //   ->save();

    // parent::submitForm($form, $form_state);

    // \Drupal::messenger()->addMessage(t('Form has been saved successfully'));








    }
}
