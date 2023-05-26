<?php
 
/**
 * @file
 * @Contains Drupal\scfp_pw_migrate\Controller\ScfpPwMigrateController.
 */

namespace Drupal\scfp_pw_migrate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;


/**
 * Implement ScfpPwMigrate class for migrate operations.
 */
class ScfpPwMigrateController extends ControllerBase {

  public function migratePodcastsOrWebcasts()
  {

    $database = \Drupal::database();
    $query = $database->select('node', 'n') 
    ->fields('n', ['nid']) 
    ->condition('n.type', 'podcast_webcasts');
    $results = $query->execute();
    $fetch_nodes = $results->fetchAll();
    // Breakdown your process into small batches(operations).
       
        $operations = [];
        foreach ($fetch_nodes as $result) {
          if(isset($result->nid) && $result->nid > 0) {
            $operations[] = ['\Drupal\scfp_pw_migrate\Controller\ScfpPwMigrateController::scfp_pw_migrate_batch_process', [$result->nid]];
        
          }
        }
        // Setup and define batch informations.
        $batch = array(
            'title' => t('Batch fix'),
            'operations' => $operations,
            'finished' => '\Drupal\scfp_pw_migrate\Controller\ScfpPwMigrateController::scfp_pw_migrate_batch_finished',
        );

        
     batch_set($batch);
      // Only needed if not inside a form _submit handler.
        // Setting redirect in batch_process.
        return batch_process('/admin/content');
  }



  /**
   * The batch processor.
   */
  public static function scfp_pw_migrate_batch_process($nid, &$context) {
    // Display a progress message...
    $context['message'] = "Now processing $nid...";
    _pw_migrate_by_node_id($nid);
  }

  /**
   * The batch finish handler.
   */
  public static function scfp_pw_migrate_batch_finished($success, $results, $operations) {
    if ($success){
      $message = count($results). ' batches processed.';
      \Drupal::messenger()->addStatus(t($message));
    }else{
      $message = 'Finished with an error.';
    \Drupal::messenger()->addError(t($message));
    }    
  }



}




