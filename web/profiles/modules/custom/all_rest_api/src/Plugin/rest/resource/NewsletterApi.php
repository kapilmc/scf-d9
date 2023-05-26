<?php

namespace Drupal\all_rest_api\Plugin\rest\resource;
use Drupal\Core\Database\Database;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Component\Serialization;
use Symfony\Component\Serializer\Encoder;
use Drupal\Component\Serialization\Json;

use Drupal\Core\File\FileSystem;
use Drupal\Core\Url;

use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "Newsletter Api",
 *   label = @Translation("Newsletter Api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-newsletter"
 *   }
 * )
 */
class NewsletterApi extends ResourceBase {

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */

    public function get() {
        // dd('csbjcs');
        $config = \Drupal::config('scfp_newsletter.settings');
        $configs = \Drupal::config('scfp_newsletter_external.settings');

        
      $response['links'] = $this->fetchNewsletterLinks();
      $response['newsletter'] = $this->fetchNewsletter();
   

      $info_text = $config->get('scfp_newsletter_block_title');
      $meta = [
      'info_text'=> !empty($info_text) ? $info_text : 'Subscribe To Our Newsletters',
      'btn_text' => $configs->get('scfp_newsletter_block_button_text'),
      ];
      $result = [ 'status'=>"success", 'data'=>$response, 'meta'=>$meta];
        $results = new ResourceResponse($result);
     
        //  return  jsonsuccess($results);
      return $results;
   
    }

    public function fetchNewsletter() {
      $template = $response = '';
      $config = \Drupal::config('scfp_newsletter.settings');
      $details =  \Drupal::database()->select('scfp_newsletter_ordering', 'n');
      $details ->orderBy('n.weight', 'asc');
      $details ->fields('n', ['id','newsletter_title','newsletter_email','newsletter_tooltip']);
      $results = $details->execute()->fetchAll();
        // ->select(['n.id as id','n.newsletter_title as title','n.newsletter_email as email','newsletter_tooltip as tooltip'])
        
        // ->get();

      $subscription_success = $config->get('scfp_newsletter_subscription_submit') ? $config->get('scfp_newsletter_subscription_submit') : 'Subscribed successfully';
      $subscription_template = $config->get('scfp_newsletter_subscription_email') ? $config->get('scfp_newsletter_subscription_email') : $template;
      
      foreach ($results as $key => $value) {
        if( strpos( $subscription_success, '[newsletter name]' ) !== false ) {
          $message = str_replace('[newsletter name]', $value->title, $subscription_success);
        }
        if( strpos( $subscription_template, '[newsletter name]' ) !== false ) {
          $email_template = str_replace('[newsletter name]', $value->title, $subscription_template);
          if( strpos( $email_template, '[newsletter email ID]' ) !== false ) {
            $email_template = str_replace('[newsletter email ID]', $value->email, $email_template);
          }
        }


        $data[] = array(
            'id'=> $value->id,
            'title'=> $value->newsletter_title,
            'email'=> $value->newsletter_email,
            'tooltip'=> $value->newsletter_tooltip,
   
           );

      }
  
  
      $response = $data;
      return $response;
    }
  
    /**
     * fetchNewsletterLinks
     * @return array
     */
    public function fetchNewsletterLinks() {

      $response = [];


      $data = \Drupal::database()->select('scfp_newsletter_links_ordering', 'n');
      $data->orderBy('n.weight', 'asc');
        // ->select(['n.link_title as title','n.link_url as url'])
        $data->fields('n', ['link_title','link_url']);
        $results = $data->execute()->fetchAll();
        $data = [];
        foreach($results as $key => $value) {
       
       $data[] = array(
      
          'title'=> $value->link_title,
          'url'=> $value->link_url,
         
 
         );
       
         }
 
     $response['data'] = $data;
     return  $response;


    }


}




