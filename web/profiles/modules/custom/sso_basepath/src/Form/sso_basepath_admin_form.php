<?php
/**
 * @file
 * Contains \Drupal\sso_basepath\Form\sso_basepath_admin_form.
 */
namespace Drupal\sso_basepath\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;


class sso_basepath_admin_form extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sso_basepath_admin_form';
  }

    /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames()
  {
    return [
      'sso_basepath_admin.settings',
    ];
  }


  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */

  public function buildForm(array $form, FormStateInterface $form_state) {
   
    $config = $this->config('sso_basepath_admin.settings');

    $form['sso_config_domain'] = array(
      '#title' => 'Main domain URL',
      '#description' => 'SSO domain for this website eg: http://devhome.intranet.mckinsey.com',
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $config->get('sso_config_domain', 'http://devhome.intranet.mckinsey.com'),
  );
  $form['sso_config_base_path'] = array(
      '#title' => 'Base Path',
      '#description' => 'SSO base path for this website eg: /globalcomm/[projectname]/',
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $config->get('sso_config_base_path', '/globalcomm/sjod7/'),
  );
  $form['sso_config_check_header'] = array(
      '#title' => 'Header Var',
      '#description' => '$_SERVER variable name which will make difference between sso or non sso url.',
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $config->get('sso_config_check_header', 'HTTP_FMNO'),
  );
  // return system_settings_form($form);
 
  return parent::buildForm($form, $form_state);

    //  return $form;

     }

 public function validateForm(array &$form, FormStateInterface $form_state) {


    }
    public function submitForm(array &$form, FormStateInterface $form_state) {

      foreach ($form_state->getValues() as $key => $value) {
        $this->config('sso_basepath_admin.settings')
            ->set($key, $value)->save();

        parent::submitForm($form, $form_state);
    }
     

       }




}