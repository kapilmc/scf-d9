<?php

namespace Drupal\custom_rest_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Database\Database;
/**
 * Provides REST API for Content Based on URL.
 *
 * @RestResource(
 *   id = "hottopics_resource",
 *   label = @Translation("Hot Topics  API"),
 *   uri_paths = {
 *     "canonical" = "vi/api/get-hot-topics"
 *   }
 * )
 */


class HotTopicsApi extends ResourceBase {

/**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   Returning rest resource.
   */
  public function get() {

     $response = ['message' => 'Hello, get-hot-topics api'];


    return new ResourceResponse($response);



//     $response = [];
//     $limit = ($request->query('limit') && $request->query('limit') > 0) ? $request->query('limit') : 10;
//     $offset = ($request->query('offset') && $request->query('offset') >= 0) ? $request->query('offset') : 0;
//     // Get hot topics documents/articles
//      $nodes = DB::table('scf_hot_topics_ordering as b')
//         ->join('node as n', 'b.nid', '=', 'n.nid')
//         ->where('n.status', self::PUBLISHED)
//         ->orderBy('b.weight', 'asc')
//         ->skip($offset*$limit)
//         ->take($limit)
//         ->get(['b.nid', 'n.type']);
//     foreach($nodes as $key => $node) {
//        if($node->type === RESOURCES_CONTENT_TYPE) {
//             $type = ['type' => DOCUMENT];
//             $temp  = $type + $this->getResourceDocumentsByNid($node->nid, NULL);

//             if(isset($temp['added_toolbox'])){
//               unset($temp['added_toolbox']);
//             }

//             if(isset($temp['text'])){
//               unset($temp['text']);
//             }

//             if(isset($temp['bookmarked'])){
//               unset($temp['bookmarked']);
//             }

//             if(isset($temp['authors'])){
//               unset($temp['authors']);
//             }

//             if(!empty($temp) && isset($temp['trophy'])){
//              unset($temp['trophy']);
//             }

//             $response[] = $temp;
//        }
//        if($node->type === NEWS_AND_ARTICLE_CONTENT_TYPE) {
//          $type = ['type' => ARTICLE];
//          $temp  = $type + $this->getArticleData($node->nid, NULL);

//          if(isset($temp['bookmarked'])){
//             unset($temp['bookmarked']);
//          }
//          $response[] = $temp;
//        }
//     }
//     $count = $this->getHotTopicsCount($user);
//     return  ['items' => $response, 'hot_topic_count' => $count];
// }






















  
  }


}

?>