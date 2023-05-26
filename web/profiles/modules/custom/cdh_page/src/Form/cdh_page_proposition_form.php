<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\cdh_page_proposition_form.
 */
namespace Drupal\cdh_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for cdh_page_proposition_form Config Form.
 */
class cdh_page_proposition_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'cdh_page_proposition_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_proposition.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cdh_page_proposition.settings');
        

        $cdh_proposition_descrition_1 = $config->get('cdh_proposition_descrition_1', '');
        $cdh_proposition_descrition_2 = $config->get('cdh_proposition_descrition_2', '');
        $cdh_proposition_descrition_3 = $config->get('cdh_proposition_descrition_3', '');
        $form['cdh_page_proposition_title'] = array(
          '#type' => 'textfield',
          '#title' => t('Title'),
          '#default_value' => $config->get('cdh_page_proposition_title'),
        );
      
        


        $form['cdh_proposition_title_1'] = array(
          '#type' => 'textfield',
         '#default_value' =>  $config->get('cdh_proposition_title_1'),
         '#size' => 60,
         '#maxlength' => 255,
         '#required' => false,
             );

       $form['cdh_proposition_descrition_1'] = array(
        '#type' => 'text_format',
       '#format' => isset($cdh_proposition_descrition_1['format']) ? $cdh_proposition_descrition_1['format'] : 'filtered_html',
       // '#title' => t('Description'),
       '#default_value' => isset($cdh_proposition_descrition_1['value']) ? $cdh_proposition_descrition_1['value'] : '',
       '#rows' => 6,

        );

        $form['cdh_proposition_title_2'] = array(
          '#type' => 'textfield',
         '#default_value' =>  $config->get('cdh_proposition_title_2'),
         '#size' => 60,
         '#maxlength' => 255,
         '#required' => false,
             );

       $form['cdh_proposition_descrition_2'] = array(
        '#type' => 'text_format',
       '#format' => isset($cdh_proposition_descrition_2['format']) ? $cdh_proposition_descrition_2['format'] : 'filtered_html',
       // '#title' => t('Description'),
       '#default_value' => isset($cdh_proposition_descrition_2['value']) ? $cdh_proposition_descrition_2['value'] : '',
       '#rows' => 6,

        );


        $form['cdh_proposition_title_3'] = array(
          '#type' => 'textfield',
         '#default_value' =>  $config->get('cdh_proposition_title_3'),
         '#size' => 60,
         '#maxlength' => 255,
         '#required' => false,
             );

       $form['cdh_proposition_descrition_3'] = array(
        '#type' => 'text_format',
       '#format' => isset($cdh_proposition_descrition_3['format']) ? $cdh_proposition_descrition_3['format'] : 'filtered_html',
       // '#title' => t('Description'),
       '#default_value' => isset($cdh_proposition_descrition_3['value']) ? $cdh_proposition_descrition_3['value'] : '',
       '#rows' => 6,

        );













        // $form['items']['#tree'] = TRUE;
        // $form['items'][0] = [
        //   'cdh_proposition_title_1' => [
        //     '#type' => 'textfield',
        //     '#default_value' =>  $config->get('cdh_proposition_title_1'),
        //     '#size' => 60,
        //     '#maxlength' => 255,
        //     '#required' => FALSE,
        //   ],
        //   'cdh_proposition_descrition_1' => [
        //     '#type' => 'text_format',
        //     '#format' => isset($cdh_proposition_descrition_1['format']) ? $cdh_proposition_descrition_1['format'] : 'filtered_html',
        //     //'#title' => t('Description'),
        //     '#default_value' => isset($cdh_proposition_descrition_1['value']) ? $cdh_proposition_descrition_1['value'] : '',
        //     '#rows' => 6,
        //   ],
        //   'cdh_proposition_title_2' => [
        //     '#type' => 'textfield',
        //     '#default_value' => $config->get('cdh_proposition_title_2'),
        //     '#size' => 60,
        //     '#maxlength' => 255,
        //     '#required' => FALSE,
        //   ],
        //   'cdh_proposition_descrition_2' => [
        //     '#type' => 'text_format',
        //     '#format' => isset($cdh_proposition_descrition_2['format']) ? $cdh_proposition_descrition_2['format'] : 'filtered_html',
        //    // '#title' => t('Description'),
        //     '#default_value' => isset($cdh_proposition_descrition_2['value']) ? $cdh_proposition_descrition_2['value'] : '',
        //     '#rows' => 6,
        //   ],
        //   'cdh_proposition_title_3' => [
        //     '#type' => 'textfield',
        //     '#default_value' => $config->get('cdh_proposition_title_3'),
        //     '#size' => 60,
        //     '#maxlength' => 255,
        //     '#required' => FALSE,
        //   ],
        //   'cdh_proposition_descrition_3' => [
        //     '#type' => 'text_format',
        //     '#format' => isset($cdh_proposition_descrition_3['format']) ? $cdh_proposition_descrition_3['format'] : 'filtered_html',
        //     //'#title' => t('Description'),
        //     '#default_value' => isset($cdh_proposition_descrition_3['value']) ? $cdh_proposition_descrition_3['value'] : '',
        //     '#rows' => 6,
        //   ],
        // ];
        $form['actions']['save'] = array(
          '#type' => 'submit',
          '#value' => t('Save Changes'),
          // '#submit' => array('_cdh_page_proposition_form'),
        );
        return $form;



    }
    

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('cdh_page_proposition.settings')
                ->set($key, $value)->save();
                \Drupal::messenger()->addMessage(t('Form has been saved successfully'));
            // parent::submitForm($form, $form_state);
        }
    }
}
