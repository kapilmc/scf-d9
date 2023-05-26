<?php
namespace Drupal\scfp_miscellaneous_blocks\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;


class DownloadCSVController extends ControllerBase{

    public function downloadCustomers(){
        $submissions = array();
        $query = \Drupal::database()->select('scf_feedback', 'f');
        $query->addField('f', 'mail', 'mail');
        $query->addField('f', 'name', 'name');
        $query->addField('f', 'rating', 'rating');
        $query->addField('f', 'improvement', 'improvement');
        $query->addField('f', 'well', 'well');
        $query->addField('f', 'timestamp', 'timestamp');
        $query->distinct();
        $query->orderBy('f.timestamp', 'DESC');
        $results = $query->execute()->fetchAll();
        // dd($results);
        // if ($results != null && count($results) >= 1) {
        //     $storage = \Drupal::entityTypeManager()->getStorage('scf_feedback');
        //     $submissions = $storage->loadMultiple($results);

        // }

        if(count($results) > 0) foreach ($results as $result) {
            
            $data = array(
                // $result->mail,
                // $result->name,
                // $result->name,
                // $result->name,
                // $result->name,
                date('d-M-Y', $result->timestamp),
                $result->name,
                $result->mail,
                $result->rating,
                $result->well,
                $result->improvement,
            );
            
            $rows[] = implode(',', $data);

        }
        
        $content = implode("\n", $rows);
       
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="feedback-report.csv"');
        // dd($response);
        

        return $response;
    }


    public function content() {
 
        return [
          '#theme' => 'scfp_newsletter',
          '#test_var' => $this->t('Test Value'),
        ];
     
      }


}



?>