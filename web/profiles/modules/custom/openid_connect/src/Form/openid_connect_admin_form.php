<?php
/**
 * @file
 * Contains \Drupal\openid_connect\Form\openid_connect_admin_form.
 */
namespace Drupal\openid_connect\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class for OpenidConnect Config Form.
 */
class openid_connect_admin_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'OpenidConnectAdminForm';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['openid_connect_admin.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('openid_connect_admin.settings');
          $options = $this->openid_connect_auto_login_get_all_roles();
        //   dd($options);
         $options = ['Generic','Google']; 
            $form['openid_connect_clients_enabled'] = array(
            '#title' => t('Enabled OpenID Connect clients'),
            '#description' => t('Choose enabled OpenID Connect clients.'),
            '#type' => 'checkboxes',
            '#options' => $options,
            '#default_value' => $config->get('openid_connect_clients_enabled'),
            );
            // foreach ($client_plugins as $client_plugin) {
            //     $client = openid_connect_get_client($client_plugin['name']);
                
            //     $element = 'openid_connect_clients_enabled[' . $client_plugin['name'] . ']';
            //     $form['clients'][$client_plugin['name']] = array(
            //       '#title' => $client_plugin['title'],
            //       '#type' => 'fieldset',
            //       '#states' => array(
            //     'visible' => array(
            //       ':input[name="' . $element . '"]' => array('checked' => true),
            //         ),
            //       ),
            //     );
            //     $form['clients'][$client_plugin['name']] += $client->settingsForm();

            $form['client_id'] = array(
                '#title' => t('Client ID'),
                '#type' => 'textfield',
                '#default_value' => $config->get('client_id'),
                '#states' => array(
                'visible' => array(
                ':input[name="openid_connect_clients_enabled[0]"]' => array('checked' => true),
                // 'or',
                //  ':input[name="openid_connect_clients_enabled[1]"]' => array('checked' => true),
                ),
                ),
                );
            
                $form['client_secret'] = array(
                '#title' => t('Client secret'),
                '#type' => 'textfield',
                '#default_value' => $config->get('client_secret'),
                '#states' => array(
                'visible' => array(
                ':input[name="openid_connect_clients_enabled[0]"]' => array('checked' => true),
                // 'and',
                // ':input[name="openid_connect_clients_enabled[1]"]' => array('checked' => true),
                ),
                ),
                );
                $form['authorization_endpoint'] = array(
                '#title' => t('Authorization endpoint'),
                '#type' => 'textfield',
                '#default_value' => $config->get('authorization_endpoint'),
                '#states' => array(
                'visible' => array(
                ':input[name="openid_connect_clients_enabled[0]"]' => array('checked' => true),
                ),
                ),
                );
                $form['token_endpoint'] = array(
                '#title' => t('Token endpoint'),
                '#type' => 'textfield',
                '#default_value' => $config->get('token_endpoint'),
                '#states' => array(
                'visible' => array(
                ':input[name="openid_connect_clients_enabled[0]"]' => array('checked' => true),
                ),
                ),
                );
                $form['userinfo_endpoint'] = array(
                '#title' => t('UserInfo endpoint'),
                '#type' => 'textfield',
                '#default_value' => $config->get('userinfo_endpoint'),
                '#states' => array(
                'visible' => array(
                ':input[name="openid_connect_clients_enabled[0]"]' => array('checked' => true),
                ),
                ),
                );

                $form['openid_connect_always_save_userinfo'] = array(
                '#title' => t('Save user claims on every login'),
                '#description' => t('If disabled, user claims will only be saved when the account is first created.'),
                '#type' => 'checkbox',
                '#default_value' => $config->get('openid_connect_always_save_userinfo'),
                );
        
                //  if (variable_get('user_pictures')) {
                $form['openid_connect_user_pictures'] = array(
                '#title' => t('Fetch user profile picture from login provider'),
                '#description' => t('Whether the user profile picture from the login provider should be fetched and saved locally.'),
                '#type' => 'checkbox',
                '#default_value' => $config->get('openid_connect_user_pictures'),
                );
                //     }
        
                $form['openid_connect_connect_existing_users'] = array(
                '#title' => t('Automatically connect existing users'),
                '#description' => t('If disabled, authentication will fail for existing email addresses.'),
                '#type' => 'checkbox',
                // This is FALSE to reflect the default behaviour of historical installs.
                // Given the install base there is a high likelyhood that existing sites
                // are using the module as the single account creation process.
                '#default_value' => $config->get('openid_connect_connect_existing_users'),
                );
        


             
            //    $user_entity_wrapper = entity_metadata_wrapper('user');
            //    dd( $user_entity_wrapper);
            //    dd('csdvdf');
            //    dd($user_entity_wrappe);
            //    $claims = openid_connect_claims_options();
            //    $properties = $user_entity_wrapper->getPropertyInfo();
            //    $properties_skip = _openid_connect_user_properties_to_skip();
            //    foreach ($properties as $property_name => $property) {
            //    if (isset($properties_skip[$property_name])) {
            //   continue;
            //  }
            // Always map the timezone.
        //    $default_value = 0;
          // if ($property_name == 'timezone') {
         //   $default_value = 'zoneinfo';
          // }

           $form['userinfo_mapping']['openid_connect_userinfo_mapping_property_' . $property_name] = array(
           '#type' => 'select',
        //   '#title' => $property['label'],
        //  '#description' => $property['description'],
        //   '#options' => $claims,
         '#empty_value' => 0,
         '#empty_option' => t('- No mapping -'),
         '#default_value' => $config->get('openid_connect_userinfo_mapping_property_' . $property_name, $default_value),
    );




            //    }





                // $form['userinfo_mapping'] = array(
                //     '#title' => t('User claims mapping'),
                //     '#type' => 'fieldset',
                //     '#empty_option' => t('- No mapping -'),
                //   );
                    

                //   $user_entity_wrapper = entity_metadata_wrapper('user');
                //   $node->get('field_job_title')->getValue(user);
                //   dd($node);
                //   $claims = openid_connect_claims_options();
                //   dd($claims);
                //   $properties = $user_entity_wrapper->getPropertyInfo();
                //   $properties_skip = _openid_connect_user_properties_to_skip();
                // foreach ($properties as $property_name => $property) {
                //     if (isset($properties_skip[$property_name])) {
                //         continue;
                    // }
                    // Always map the timezone.
                    // $default_value = 0;
                    // if ($property_name == 'timezone') {
                    //   $default_value = 'zoneinfo';
                    // }

                    // $form['userinfo_mapping']['openid_connect_userinfo_mapping_property_' . $property_name] = array(
                    // '#title' => $property['label'],
                    // '#description' => $property['description'],
                    // '#options' => $claims,
                    //   '#empty_value' => 0,
                    //   '#empty_option' => t('- No mapping -'),
                    //   );

                    //   $form['person_id'] = array(
                    //   '#title' => t('Person Id'),
                    //   '#description' => t('Field"field_person_id".'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //   'select' => t('-No mapping-')
                    //   ]
                    //   );

                    //   $form['user_expertise'] = array(
                    //   '#title' => t('User expertise'),
                    //   '#description' => t('Field"field_user_expertise".'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );

                    //   $form['fmno'] = array(
                    //   '#title' => t('FMNO'),
                    //   '#description' => t('Field"field_user_fmno".'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );
                    
                    //   $form['profile_image'] = array(
                    //   '#title' => t('Profile Image'),
                    //   '#description' => t('Field"field_profile_image".'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );    

                    //   $form['role'] = array(
                    //   '#title' => t('Role'),
                    //   '#description' => t('Field"field_user_role".'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );
                    
                    //   $form['location'] = array(
                    //   '#title' => t('Location'),
                    //   '#description' => t('Field"field_user_location".'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );  
                         
                    //   $form['feeds_guid'] = array(
                    //   '#title' => t('Feeds Item GUID'),
                    //   '#description' => t('Feeds Item GUID.'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );

                    //   $form['feeds_url'] = array(
                    //   '#title' => t('Feeds Item URL'),
                    //   '#description' => t('Feeds Item URL.'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );

                    //   $form['feed_nid'] = array(
                    //   '#title' => t('Feed NID'),
                    //   '#description' => t('Nid of the Feed Node that imported this entity.'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );
                    
                    //   $form['feed_node'] = array(
                    //   '#title' => t('Feed node'),
                    //   '#description' => t('Feed Node that imported this entity.'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );
                
                    //   $form['time_zone'] = array(
                    //   '#title' => t('Time zone'),
                    //   '#description' => t('The users time zone.'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );  
                    
                    //   $form['uuid'] = array(
                    //   '#title' => t('UUID'),
                    //   '#description' => t('The universally unique ID.'),
                    //   '#type' => 'select',
                    //   '#options' => [
                    //       'select' => t('-No mapping-')
                    //   ]
                    //   );  
                      $form['submit'] = array(
                      '#type' => 'submit',
                      '#value' => t('Save configuration'),
                      );
            
                      return $form;
    
                }


                // public function validateForm(array&$form, FormStateInterface $form_state)
                // {

                // }
    // }



    public function openid_connect_auto_login_get_all_roles()
    {
        $roles = \Drupal::entityQuery('user_role')
            ->condition('status', 1)
            ->execute();
            // dd($roles);
        return $roles;
        // dd($roles);
    }


            
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        foreach ($form_state->getValues() as $key => $value) {
            $this->config('openid_connect_admin.settings')
                ->set($key, $value)->save();

            parent::submitForm($form, $form_state);
        }
    }

                //     $claims = array();
                // foreach ($form_state['values']['userinfo_mapping'] as $variable => $claim) {
                //     if (!empty($claim)) {
                //         $claims[] = $claim;
                //         variable_get($variable, $claim);
                //     }
                //     else {
                //         variable_del($variable);
                //     }
                // }
                // if (!empty($claims)) {
                //     variable_set('openid_connect_userinfo_mapping_claims', $claims);
                // }
                // else {
                //     variable_del('openid_connect_userinfo_mapping_claims');
                // }
                //     $default_enabled = array(
                //     'openid_connect_always_save_userinfo',
                //     'openid_connect_user_pictures',
                //     'openid_connect_connect_existing_users',
                //     );
                //     foreach ($default_enabled as $variable) {
                //         if (isset($form_state['values'][$variable])) {
                //             variable_set($variable, $form_state['values'][$variable]);
                //         }
                //     }

}
