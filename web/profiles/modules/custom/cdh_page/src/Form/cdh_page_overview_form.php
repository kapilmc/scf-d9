<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\cdh_page_overview_form.
 */
namespace Drupal\cdh_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for cdh_page_overview_form Config Form.
 */
class cdh_page_overview_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'cdh_page_overview_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_overview.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cdh_page_overview.settings');
        
        $overview = $config->get('cdh_page_overview', '');
        $form['cdh_page_overview_title'] = array(
          '#type' => 'textfield',
          '#title' => t('Title'),
          '#default_value' => $config->get('cdh_page_overview_title'),
        );
        $form['cdh_page_overview'] = array(
          '#type' => 'text_format',
          '#format' => isset($overview['format']) ? $overview['format'] : 'filtered_html',
          '#title' => t('Description'),
          '#default_value' => isset($overview['value']) ? $overview['value'] : '',
          // ' #default_value' => $config->get('cdh_page_overview'),
          '#rows' => 6,
        );
        $form['cdh_page_overview_footer_text'] = array(
          '#type' => 'textfield',
          '#title' => t('Footer Text'),
          '#default_value' => $config->get('cdh_page_overview_footer_text'),
        );
        $form['actions']['save'] = array(
          '#type' => 'submit',
          '#value' => t('Save Changes'),
      
        );

        return $form;

    }
    

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('cdh_page_overview.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }
    }
}
