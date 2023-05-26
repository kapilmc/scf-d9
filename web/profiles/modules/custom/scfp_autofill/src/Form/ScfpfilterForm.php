<?php
namespace Drupal\scfp_autofill\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

/**
 * Class ScfpfilterForm.
 *
 * Drupal\scfp_autofill\Form
 */
class ScfpfilterForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'scfp_filter_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        
        // $form['filters'] = [
        // '#type'  => 'fieldset',
        // '#title' => $this->t('Filter'),
        // '#open'  => true,
        // ];

        $form['filters']['search'] = [
        '#title'         => 'Search',
        '#type'          => 'search'
        
        ];
        $form['filters']['actions'] = [
        '#type'       => 'actions'
        ];

        $form['filters']['actions']['submit'] = [
        '#type'  => 'submit',
        '#value' => $this->t('Search')
        ];
   
        return $form;

    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
     
        if ($form_state->getValue('search') == "") {
            $form_state->setErrorByName('from', $this->t('You must enter a valid first name.'));
        }
     
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array & $form, FormStateInterface $form_state)
    {      
        $field = $form_state->getValues();
        $search = $field["search"];
        $url = \Drupal\Core\Url::fromRoute('scfp_autofill.stuff')
          ->setRouteParameters(array('search'=>$search));
        $form_state->setRedirectUrl($url); 
    }

}
