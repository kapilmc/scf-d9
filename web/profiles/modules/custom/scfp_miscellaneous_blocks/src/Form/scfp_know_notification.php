<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\scfp_know_notification
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\ConfigFormBase;
 

class scfp_know_notification extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'scfp_know_notification.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_know_notification';
  }
   /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      // static::SETTINGS,
      'scfp_know_notification.settings'
    ];
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('scfp_know_notification.settings');
        
            $form['know_notification_mails'] = array(
                '#type' => 'textarea',
                '#title' => t('Mails'),
                '#default_value' =>$config->get('know_notification_mails', ''),
                '#description' => t('Please enter mails(One email per line) which will receive know preview updated mail'),
                '#required' => TRUE,
                );
                $form['know_notification_mail_subject'] = array(
                  '#type' => 'textfield',
                  '#title' => t('E-mail Subject'),
                  '#default_value' => $config->get('know_notification_mail_subject', ''),
                  '#description' => t('Know document notification mail subject'),
                  '#required' => TRUE,
                );
                $description = 'Know document notification mail body';
                $description .= '<p>Tokens: <br/>:updated_documents => List All updated know document</p>';
            
                $form['know_notification_mail_body'] = array(
                  '#type' => 'textarea',
                  '#title' => t('E-mail Body'),
                  '#default_value' => $config->get('know_notification_mail_body', ''),
                  '#description' => t($description),
                  '#required' => TRUE,
                );
            
    $form['actions']['save'] = array(
        '#type' => 'submit',
        '#value' => t('Save congiguration'),
      
      );

    return $form;


  }






  public function validateForm(array &$form, FormStateInterface $form_state) {

}

public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $this->config('scfp_know_notification.settings')
 
    ->set('know_notification_mails',$form_state->getValue('know_notification_mails'))
    ->set('know_notification_mail_subject',$form_state->getValue('know_notification_mail_subject'))
    ->set('know_notification_mail_body',$form_state->getValue('know_notification_mail_body'))

  
  
    ->save();

    \Drupal::messenger()->addMessage('The configuration options have been saved.');
     
    
    }
  




    function _scfp_know_preview_updated_cron_callback() {
        if (!isKnowDocumentsUpdated() && !isKnowDocumentsSizeUpdated())
          return
      
      
        $notify_mails = explode(PHP_EOL, variable_get('know_notification_mails', ''));
      
        $emails = array_map(function($el){
          return trim($el);
        }, explode(PHP_EOL, variable_get('know_notification_mails', '')));
      
        $emails = array_filter($emails, function($el) {
          return $el ? true : false;
        });
      
        $updated_documents = '';
        if(isKnowDocumentsUpdated()) {
          $updated_documents .= _get_know_preview_updated_list();
        }
      
        if(isKnowDocumentsSizeUpdated()) {
          $updated_documents .= _get_know_attachment_updated_list();
        }
      
        if(!$updated_documents)
          return;
      
        $replace = [
          ':updated_documents' => $updated_documents,
        ];
      
        $body = variable_get('know_notification_mail_body', '');
        $body = strtr($body, $replace);
      
        $params = [
          'subject' => variable_get('know_notification_mail_subject', ''),
          'body' => $body,
        ];
        $from = 'Strategy & Corporate Finance <strategy_platform@mckinsey.com>';
      
        foreach($emails as $email) {
          drupal_mail('scfp_miscellaneous_blocks', 'know_preview_notify', $email, language_default(), $params, $from);
        }
      
        db_delete('scfp_know_doc_updated')->execute();
      }
      
      function isKnowDocumentsUpdated() {
        return (bool)db_select('scfp_know_doc_updated', 'doc')->fields("doc", ["nid"])->condition('doc.type', KNOW_PREVIEW_UPDATED)->range(0, 1)->execute()->fetchField();
      }
      
      function isKnowDocumentsSizeUpdated() {
        return (bool)db_select('scfp_know_doc_updated', 'doc')->fields("doc", ["nid"])->condition('doc.type', KNOW_ATTACHMENT_UPDATE)->range(0, 1)->execute()->fetchField();
      }
      
      function _get_know_attachment_updated_list(){
        $header =['Title', 'Journey', 'Know ID'];
        $rows = [];
        $query = db_select('scfp_know_doc_updated', 'doc')
        ->fields("doc");
        $query->join('node', 'n', 'n.nid = doc.nid');
        $query->fields("n", ["title"])->condition('doc.type', KNOW_ATTACHMENT_UPDATE);
        $results = $query->execute();
      
        if(!$results)
          return '';
      
        while ($item = $results->fetchObject()) {
          $row = [];
          $journey = array_reduce(getDocumentJourney($item->nid), function($a, $i){
            $a[] = $i->name;
            return $a;
          },[]);
      
          $row["title"] = $item->title;
          $row["journey"] = implode(',', $journey);
          $row["know_id"] = $item->know_id;
          $rows[] = $row;
        }
        $head_caption = '<p>Know attachment updated for following document</p>';
        return $head_caption.theme('scfp_know_notification_mail', ['header' => $header, 'rows' => $rows]);
      }
      
      function _get_know_preview_updated_list() {
        $header =['Title', 'Journey', 'Know ID', 'Preview'];
        $rows = [];
        $query = db_select('scfp_know_doc_updated', 'doc')
        ->fields("doc");
        $query->join('node', 'n', 'n.nid = doc.nid');
        $query->fields("n", ["title"])->condition('doc.type', KNOW_PREVIEW_UPDATED);
        $results = $query->execute();
      
        if(!$results)
          return '';
      
        while ($item = $results->fetchObject()) {
          $row = [];
          $journey = array_reduce(getDocumentJourney($item->nid), function($a, $i){
            $a[] = $i->name;
            return $a;
          },[]);
      
          $row["title"] = $item->title;
          $row["journey"] = implode(',', $journey);
          $row["know_id"] = $item->know_id;
          $row["preview"] = $item->preview ? 'Added' : 'Removed';
          $rows[] = $row;
        }
        $head_caption = '<p>Know preview updated for following document</p>';
        return $head_caption.theme('scfp_know_notification_mail', ['header' => $header, 'rows' => $rows]);
      }
      






}











