<?php
/**
 * @file
 * Contains \Drupal\scfp_miscellaneous_blocks\Form\scfp_feedback_report.
 */
namespace Drupal\scfp_miscellaneous_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class scfp_feedback_report extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scfp_feedback_report';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    //$url = Url::fromRoute('scfp_miscellaneous_blocks.csvdownload');

  //   $routename = 'scfp_miscellaneous_blocks.csvdownload';
  // $url = \Drupal\Core\Url::fromRoute($routename);
  //dd($url);
  // make the redirection
    //     '#type' => 'submit',
  //  $form['#action']['submit'] = $url->toString();

  // $form['actions']['download']['#submit'][] = 'scfp_miscellaneous_blocks.csvdownload';

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'Download csv',
        // '#url' =>$url,
];


    $query = \Drupal::database()->select('scf_feedback', 'f');


    $query->addField('f', 'mail', 'mail');
    $query->addField('f', 'name', 'name');
    $query->addField('f', 'rating', 'rating');
    $query->addField('f', 'improvement', 'improvement');
    $query->addField('f', 'well', 'well');
    $query->addField('f', 'timestamp', 'timestamp');
    $query->distinct();
    $query->orderBy('f.timestamp', 'DESC');
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);
    $results = $pager->execute()->fetchAll();
    $result = $query->execute()->fetchAll();

//   dd($result);

    $rows = [];
    foreach ($result as $key => $item) {

        // dd( $item);

      $rows[$key] = [
        date('d-M-Y', $item->timestamp),
        $item->name,
        $item->mail,
        $item->rating,
        $item->well,
        $item->improvement,
      ];
    }

$header = [
    'Date',
    'User name',
    'User e-mail',
    'Site rating',
    'What works well',
    'What needs improving',
  ];
//   }
  




$form['table'] = [
'#type' => 'table',
'#header' => $header,
'#rows' => $rows,
'#empty' => t('No content has been found.'),
];

$form['pager'] = [
 '#type' => 'pager'
];

 return $form;
  }




function downloadCsv() {

      }
      
    
    
    
    
    

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {



$redirect_to_thankyou = new RedirectResponse(Url::fromUserInput('/admin/admin-dashboard/feedback-report-download')->toString());
$redirect_to_thankyou->send();
//     $query = \Drupal::database()->select('scf_feedback', 'f');
//     $query->addField('f', 'mail', 'mail');
//     $query->addField('f', 'name', 'name');
//     $query->addField('f', 'rating', 'rating');
//     $query->addField('f', 'improvement', 'improvement');
//     $query->addField('f', 'well', 'well');
//     $query->addField('f', 'timestamp', 'timestamp');
//     $query->distinct();
//     $query->orderBy('f.timestamp', 'DESC');
//     $result = $query->execute()->fetchAll();

//   //  ;


// // dd($rows);


//     // if(count($results) > 0) foreach ($results as $result) {
//       foreach ($result as $result) {
//       $data = array(
//           date('d-M-Y', $result->timestamp),
//           $result->name,
//           $result->mail,
//           $result->rating,
//           $result->well,
//           $result->improvement,
//       );
//       // dd( $data);
//       $rows[] = implode(',', $data);
//     }





// // dd($rows);

//       // // $rows = [];
//       // $rows[] = [
//       //   'Date',
//       //   'User name',
//       //   'User e-mail',
//       //   'Site rating',
//       //   'What works well',
//       //   'What needs improving',
//     //   // ]; 
//     //    $rows = [];
//     //   $rows = [
//     //     "timestamp" => $data ->timestamp,
//     //     "name" => $data -> name,
//     //     "mail" => $data -> mail,
//     //     "rating" => $data -> rating,
//     //     "well" => $data -> well,
//     //     "improvement" => $data -> improvement,

      
//     // ];


      
//   // }



  
  
//   // dd( $rows);
//   $content = implode("\n", $rows);
 
//   $response = new Response($content);
//   $response->headers->set('Content-Type', 'text/csv');
//   $response->headers->set ('Content-Description','File Download');
//   $response->headers->set('Content-Disposition','attachment; filename="feedback-report.csv"');

//   //dd($response);

//   return $response;







  }

}