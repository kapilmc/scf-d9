<?php
/**
 * @file
 * Contains \Drupal\email_template\Form\EmailTemplateAdminForm.
 */
namespace Drupal\email_template\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\editor\Ajax\EditorDialogSave;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\RedirectCommand;

 

class email_template_admin_form extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return ' email_template_admin_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {


    $conn = Database::getConnection();
    $data = [];
    if (isset($_GET['id'])) {
        $query = $conn->select('email_template_details', 'm')
            ->condition('id', $_GET['id'])
            ->fields('m');
        $data = $query->execute()->fetchAssoc();
        
       

    }

    $form['name'] = array(
                '#type' => 'textfield',
                '#title' => t('Name'),
                '#required' => TRUE,
                
                '#default_value' => (isset($data['name']) && $_GET['id']) ? $data['name']:'',

              );
              $form['body'] = array(
                '#title' => t('Body'),
                '#type' => 'textarea',
                '#required' => TRUE,
                '#rows' => 20,
                '#default_value' => (isset($data['body']) && $_GET['id']) ? $data['body']:'',

              );
              $form['submit'] = array(
                '#type' => 'submit',
                '#value' => t('Save'),
              );

          
              $form['actions']['cancel'] = array(
                '#type' => 'button',
                '#value' => t('Cancel'),
                '#prefix' => '&nbsp; &nbsp; &nbsp;',
                '#attributes' => [
                  'class' => ['btn', 'btn-danger'],
                ],
                //'#attributes' => array('onClick' => 'history.go(-1); return true;'),
                '#ajax' => [
                  'callback' => '::closeEmailTemplateForm',
                  'event' => 'click',
                  'progress' => [
                    'type' => 'throbber',
                  ],
                  'wrapper' => 'first',
                ],
              );
              
              return $form;
  }


  public function closeEmailTemplateForm(array &$form, FormStateInterface &$form_state)
  {
    $response = new AjaxResponse();
    $command = new RedirectCommand('/admin/config/content/template');
    return $response->addCommand($command);
   
  }

/**
 * Cancel changes and return to previous page
 * @param array $form
 * @param FormStateInterface $form_state
 */
function MYMODULE_userpageredirect_callback(array &$form, FormStateInterface &$form_state) {
  $route_name = 'email_template.Emailtemplatedatashow';
  $form_state->setRedirect($route_name);
}



  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    // dd($form_state);

    $data = [
              'name'=> $form_state->getValue('name'),
              'body'=> $form_state->getValue('body'),
              
          ];
          $field=$form_state->getValues();
          $name=$field['name'];
          $body=$field['body'];
  
          
  
        
  
          if (isset($_GET['id'])) {
            $field  = array(
                'name'   => $name,
                'body' =>  $body,
                
            );
            $query = \Drupal::database();
            $query->update('email_template_details')
                ->fields($field)
                ->condition('id', $_GET['id'])
                ->execute();
                \Drupal::messenger()->addMessage(t("Configuration update successfully."));
                $form_state->setRedirect('email_template.Emailtemplatedatashow');
        }
         else
         {
          $field  = array(
            'name'   => $name,
            'body' =>  $body,
            
            );
             $query = \Drupal::database();
             $query ->insert('email_template_details')
                 ->fields($field)
                 ->execute();
                 \Drupal::messenger()->addMessage(t("Configuration saved successfully."));
             $response = new RedirectResponse("/admin/config/content/template");
             $response->send();
         }

  }

}









