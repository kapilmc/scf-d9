<?php
/**
 * @file
 * Contains \Drupal\scfp_engagement\Form\AdminKnow.
 */
namespace Drupal\scfp_engagement\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for adminengagement Config Form.
 */
class AdminKnow extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'AdminKnow';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['openid.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('openid.settings');
        $form['know_downlod_preview_new_old_api'] = [
            '#type' => 'radios',
            '#title' => 'New/Old API',
            '#options' => ['new' => 'New API', 'old' => 'Old API'],
            '#required' => true,
            '#default_value' => $config->get('know_downlod_preview_new_old_api'),
          ];
          $form['fmno_crypt_key'] = [
            '#type' => 'textfield',
            '#title' => 'Crypt Key',
            '#required' => true,
            '#default_value' => $config->get('fmno_crypt_key'),
          ];
          $form['know_api_environment'] = [
            '#type' => 'radios',
            '#title' => 'Default environment',
            '#options' => ['devhome' => 'QA', 'home' => 'Prod'],
            '#required' => true,
            '#default_value' => $config->get('know_api_environment'),
          ];

          //QA  API
          $form['devhome'] = [
          '#type' => 'fieldset',
          '#title' => 'QA environment API\'s',
          '#collapsible' => true,
          ];
  
          $devhome_download_document = 'http://devhome.intranet.mckinsey.com/ks/research/download/:know_id/:asset_type?ApplicationId=STRATEGY&ApplicationId2=&ApplicationId3=Strategy Portal&chargeCode=:chargeCode&er=t&download_cnt=';
  
          $form['devhome']['devhome_download_document'] = [
          '#type' => 'textfield',
          '#title' => 'Download document',
          '#default_value' => $config->get('devhome_download_document', $devhome_download_document),
          '#description' => ':know_id and :chargeCode will replace with appropriate value',
          '#maxlength' => 300,
          '#size' => 100,
          '#required' => true,
          ];
  
          $devhome_preview_document = 'http://devhome.intranet.mckinsey.com/ks/research/download/:know_id/:asset_type?ApplicationId=STRATEGY&ApplicationId2=&ApplicationId3=Strategy Portal&chargeCode=:chargeCode&er=t&download_cnt=&fetchPdf=Y&previewPdf=Y';
  
          $form['devhome']['devhome_preview_document'] = [
          '#type' => 'textfield',
          '#title' => 'Preview document',
          '#default_value' => $config->get('devhome_preview_document', $devhome_preview_document),
          '#description' => ':know_id and :chargeCode will replace with appropriate value',
          '#maxlength' => 300,
          '#size' => 100,
          '#required' => true,
          ];
  
          $form['devhome']['devhome_chargeCode'] = [
          '#type' => 'textfield',
          '#title' => 'Charge Code',
          '#default_value' => $config->get('devhome_chargeCode', 'ZZQ777'),
          '#description' => 'Chargecode to get documents',
          ];

          //Prod API
          $form['home'] = [
          '#type' => 'fieldset',
          '#title' => 'Prod environment API\'s',
          '#collapsible' => true,
          ];
  
          $home_download_document = 'http://home.intranet.mckinsey.com/ks/research/download/:know_id/:asset_type?ApplicationId=STRATEGY&ApplicationId2=&ApplicationId3=Strategy Portal&chargeCode=:chargeCode&er=t&download_cnt=';
  
          $form['home']['home_download_document'] = [
          '#type' => 'textfield',
          '#title' => 'Download document',
          '#default_value' => $config->get('home_download_document', $home_download_document),
          '#description' => ':know_id and :chargeCode will replace with appropriate value',
          '#maxlength' => 300,
          '#size' => 100,
          '#required' => true,
          ];
  
          $home_preview_document = 'http://home.intranet.mckinsey.com/ks/research/download/:know_id/:asset_type?ApplicationId=STRATEGY&ApplicationId2=&ApplicationId3=Strategy Portal&chargeCode=:chargeCode&er=t&download_cnt=&fetchPdf=Y&previewPdf=Y';
  
          $form['home']['home_preview_document'] = [
          '#type' => 'textfield',
          '#title' => 'Preview document',
          '#default_value' => $config->get('home_preview_document', $home_preview_document),
          '#description' => ':know_id and :chargeCode will replace with appropriate value',
          '#maxlength' => 300,
          '#size' => 100,
          '#required' => true,
          ];
  
          $form['home']['home_chargeCode'] = [
          '#type' => 'textfield',
          '#title' => 'Charge Code',
          '#default_value' => $config->get('home_chargeCode', 'ZZQ777'),
          '#description' => 'Chargecode to get documents',
          ];

          $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save configuration'),
          );

          return $form;

    }

    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('openid.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }
    }
    public function openid_connect_auto_login_get_all_roles()
    {
        $roles = \Drupal::entityQuery('user_role')
            ->condition('status', 1)
            ->execute();
        return $roles;
    }
}
