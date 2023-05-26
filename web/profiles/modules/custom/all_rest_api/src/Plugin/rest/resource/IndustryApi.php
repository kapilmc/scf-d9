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
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Url;
/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "industry api resource",
 *   label = @Translation("industry API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-terms/industry"
 *   }
 * )
 */


class IndustryApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

    $response = '';
    // $response = $this->getTerms();

    // $response = $this->getData();
    $response = $this->getFactivaIndustryCode();

   

    // // if($vocab_name == 'industry') {
    //   $no_industry = ['id'=> 0, 'title' => 'All industries', 'factiva_id' => $this->getAllFactivaIndustryCode()];
    //     // $response['terms']= $this->$no_industry;
    // // }    
    // $response = $this->getAllFactivaIndustryCode();
    
    //  $response = ['id'=> 0, 'title' => 'All industries'];
   
    $meta = ['info_text' => 'Terms'];


    $response = ['id'=> 0, 'title' => 'All industries', 'factiva_id' => $this->getAllFactivaIndustryCode()];

    $result = [ 'status'=>"success", 'data'=>$response,  'meta'=>$meta];

       $results = new ResourceResponse($result);
      //  $results->addCacheableDependency($result);
        return $results;

        // return jsonSuccess($results);

        // return jsonSuccess($response, $meta);


}



public function getTerms(){

    $vid = 'industry';
    // $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    // dd($terms);
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid,0,1,TRUE);

     $result = [];
    foreach ($terms as $term) {
      $result[] = array(
       'id'=> $term->tid->value,
       'title'=> $term->name->value,
      //  'factiva_id'=> $term->factiva_id->value,
       'factiva_id' => $this->getAllFactivaIndustryCode(),
      ) ;
   
  }
  return $result;
  
  
  }



  // public function getData($vocab_name) {
    public function getData() {
    dd('lasmd');
    $response = [];
    // $cache = getCache($vocab_name, SCFP_TAXONOMY_VOCAB_CACHE);
    // if($cache) {
    //   return ['terms' => $cache];
    // } else {
      $result = \Drupal::database()->select('taxonomy_vocabulary as t')
        ->where('machine_name', $vocab_name)
        ->get(['vid as v']);
      $vid = $result[0]->v;

      $parent = 0;
      $children[$vid] = [];
      $parents[$vid] = [];
      $terms[$vid] = [];

      $response = [];
      $result = \Drupal::database()->select('taxonomy_term_data as t')
        ->Join('taxonomy_term_hierarchy as h', 'h.tid', '=', 't.tid')
        ->where('vid', $vid)
        ->orderBy('t.weight', 'asc')
        ->orderBy('t.name', 'asc')
        ->select([
          't.tid as id',
          't.name as title',
          't.vid as vid',
          't.weight as weight',
          'h.parent as parent'
        ])
        ->get();


      foreach ($result as $key => $term) {
        if($vocab_name === 'industry') {
          $ind_factiva_id = $this->getFactivaIndustryCode($term->id);
          $term->factiva_id = $ind_factiva_id;
        }
        $children[$vid][$term->parent][] = $term->id;
        $parents[$vid][$term->id][] = $term->parent;
        $terms[$vid][$term->id] = $term;

      }
      $max_depth = (!isset($max_depth)) ? count($children[$vid]) : $max_depth;
      $tree = [];
      $process_parents = [];
      $process_parents[] = $parent;
      while (count($process_parents)) {
        $parent = array_pop($process_parents);
        // The number of parents determines the current depth.
        $depth = count($process_parents);
        if ($max_depth > $depth && !empty($children[$vid][$parent])) {
          $has_children = FALSE;
          $child = current($children[$vid][$parent]);
          do {
            if (empty($child)) {
              break;
            }
            //$term = $load_entities ? $term_entities[$child] : $terms[$vid][$child];
            $term = $terms[$vid][$child];
            if (isset($parents[$vid][$term->id])) {
              // Clone the term so that the depth attribute remains correct
              // in the event of multiple parents.
              $term = clone $term;
            }
            $term->depth = $depth;
            $term->parents = $parents[$vid][$term->id];
            unset($term->parent);
            unset($term->depth);
            unset($term->uuid);
            unset($term->description);
            unset($term->format);
            unset($term->weight);
            unset($term->vid);
            unset($term->parents);
            $tree[] = $term;
            if (!empty($children[$vid][$term->id])) {
              $has_children = TRUE;

              // We have to continue with this parent later.
              $process_parents[] = $parent;
              // Use the current term as parent for the next iteration.
              $process_parents[] = $term->id;

              // Reset pointers for child lists because we step in there more often
              // with multi parents.
              reset($children[$vid][$term->id]);
              // Move pointer so that we get the correct term the next time.
              next($children[$vid][$parent]);
              break;
            }
          } while ($child = next($children[$vid][$parent]));

          if (!$has_children) {
            // We processed all terms in this hierarchy-level, reset pointer
            // so that this function works the next time it gets called.
            reset($children[$vid][$parent]);
          }
        }
      }
      // setForeverCache($vocab_name, $tree, SCFP_TAXONOMY_VOCAB_CACHE);
    // }
    return ['terms' => $tree];
  }






























  public function getAllFactivaIndustryCode() {
    $factiva = [];
    $id = \Drupal::database()->select('taxonomy_term__field_factiva_industry' , 'f');
    $id->Join('paragraph__field_factiva_id', 'i', 'i.entity_id = f.field_factiva_industry_target_id');
    $id->fields('i', ['field_factiva_id_value']);
    $result = $id->execute()->fetchAll();
    if (is_array($result)) {
      foreach ($result as $key => $value) {
          $temp = '';
          $temp = strtoupper(trim($value->field_factiva_id_value));
          if(!in_array($temp, $factiva)) {
            $factiva[] = $temp;
          }
      }
    }
    
    return $factiva;
  }



  // public function getFactivaIndustryCode($term_id) {
    public function getFactivaIndustryCode() {
    
      $factiva = [];
      $id = \Drupal::database()->select('taxonomy_term__field_factiva_industry', 'f');
      $id->Join('paragraph__field_factiva_id', 'i', 'i.entity_id = f.field_factiva_industry_target_id');
      // $id ->where('f.entity_id', $term_id);
      // $id ->condition('f.entity_id',  $terms);

      $id->fields('i', ['field_factiva_id_value']);
      $result = $id->execute()->fetchAll();

          // ->get(['field_factiva_id_value as factiva_id']);
      if (is_array($result)) {
          foreach ($result as $key => $value) {
              $temp = '';
              $temp = strtoupper(trim($value->field_factiva_id_value));
              if (!in_array($temp, $factiva)) {
                  $factiva[] = $temp;
              }
          }
      }
      dd($factiva);
      return $factiva;
  }







}

?>