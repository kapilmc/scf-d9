<?php
/**
 * Contains \Drupal\scf_hit_page\Form\hit_page_request_a_hit_form.
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
use Symfony\Component\HttpFoundation\RedirectResponse;

class hit_page_request_a_hit_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'hit_page_request_a_hit_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['hit_page_request.settings'];
    }
  
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $config = $this->config('hit_page_request.settings');
     
    //    $form['request_hit'] = [
    //         '#title' => $this->t('Request a HIT'),
    //         '#type' => 'link',
    //         '#url' => Url::fromRoute('scf_hit_page.hit_page_request_a_hit_form'),
    //       ];
    //       $form['CST_Journey'] = [
    //         '#title' => $this->t('CST Journey Map'),
    //         '#type' => 'link',
    //         '#url' => Url::fromRoute('scf_hit_page.hit_page_journey_form'),
    //       ];

    // $form['request_hit'] = [
    //     '#title' => $this->t('Request a HIT'),
    //     '#type' => 'link',
    //     '#url' => Url::fromRoute('scf_hit_page.hit_page_request_a_hit_form'),
    //   ];
    //   $form['journery'] = [
    //     '#title' => $this->t('CST Journey Map'),
    //     '#type' => 'link',
    //     '#url' => Url::fromRoute('scf_hit_page.hit_page_journey_form'),
    //   ];
  

        // drupal_set_title('Request a HIT');
        // $cat_markup = '<ul>';
        // $cat_markup .= '<li>' . l('Request a HIT', 'admin/hit-page/request-a-hit') . '</li>';
        // $cat_markup .= '<li>' . l('CST Journey Map', 'admin/hit-page/request-a-hit/cst-journey-map') . '</li>';
        // $cat_markup .= '</ul>';
        // $form['resource_category'] = [
        //   '#type' => 'item',
        //   '#markup' => $cat_markup,
        // ];
        $request_a_hit_descrition_1 = $config->get('request_a_hit_descrition_1', '');
        $request_a_hit_descrition_2 = $config->get('request_a_hit_descrition_2', '');
        $request_a_hit_descrition_3 = $config->get('request_a_hit_descrition_3', '');
        $form['request_a_hit_title'] = array(
          '#type' => 'textfield',
          '#title' => t('Title'),
          '#default_value' => $config->get('request_a_hit_title'),
        );
    
        $form['request_a_hit_footer_text'] = array(
          '#type' => 'textfield',
          '#title' => t('Footer Text'),
          '#default_value' => $config->get('request_a_hit_footer_text'),
        );
    
        // $form['items']['#tree'] = TRUE;
        $form['items'][0] = [
          'request_a_hit_title_1' => [
            '#type' => 'textfield',
            '#default_value' =>  $config->get('request_a_hit_title_1'),
            '#size' => 60,
            '#maxlength' => 255,
            '#required' => FALSE,
          ],
          'request_a_hit_descrition_1' => [
            '#type' => 'text_format',
            '#format' => isset($request_a_hit_descrition_1['format']) ? $request_a_hit_descrition_1['format'] : 'filtered_html',
            //'#title' => t('Description'),
            '#default_value' => isset($request_a_hit_descrition_1['value']) ? $request_a_hit_descrition_1['value'] : '',
            '#rows' => 6,
          ],
          'request_a_hit_title_2' => [
            '#type' => 'textfield',
            '#default_value' => $config->get('request_a_hit_title_2'),
            '#size' => 60,
            '#maxlength' => 255,
            '#required' => FALSE,
          ],
          'request_a_hit_descrition_2' => [
            '#type' => 'text_format',
            '#format' => isset($request_a_hit_descrition_2['format']) ? $request_a_hit_descrition_2['format'] : 'filtered_html',
            // '#title' => t('Description'),
            '#default_value' => isset($request_a_hit_descrition_2['value']) ? $request_a_hit_descrition_2['value'] : '',
            '#rows' => 6,
          ],
          'request_a_hit_title_3' => [
            '#type' => 'textfield',
            '#default_value' => $config->get('request_a_hit_title_3'),
            '#size' => 60,
            '#maxlength' => 255,
            '#required' => FALSE,
          ],
          'request_a_hit_descrition_3' => [
            '#type' => 'text_format',
            '#format' => isset($request_a_hit_descrition_3['format']) ? $request_a_hit_descrition_3['format'] : 'filtered_html',
            //'#title' => t('Description'),
            '#default_value' => isset($request_a_hit_descrition_3['value']) ? $request_a_hit_descrition_3['value'] : '',
            '#rows' => 6,
          ],


        //   $form['request_a_hit_footer_text'] =[
        //     '#type' => 'textfield',
        //     '#title' => t('Footer Text'),
        //     '#default_value' => $config->get('request_a_hit_footer_text'),
        //   ],


        ];

        $form['actions']['save'] = array(
          '#type' => 'submit',
          '#value' => t('Save Changes'),
        //   '#submit' => array('_hit_page_request_a_hit_form'),
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
            $this->config('hit_page_request.settings')
                ->set($key, $value)->save();
    
            // parent::submitForm($form, $form_state);
            \Drupal::messenger()->addMessage('Form has been saved successfully');
        }
    }
}