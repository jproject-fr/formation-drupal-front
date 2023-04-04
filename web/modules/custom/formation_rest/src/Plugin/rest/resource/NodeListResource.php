<?php

namespace Drupal\formation_rest\Plugin\rest\resource;

use Drupal\rest\ResourceResponse;

/**
 * Class Transaction to return the response external.
 *
 * @RestResource(
 *   id = "formation_node_list",
 *   label = @Translation("Nodes"),
 *   uri_paths = {
 *     "canonical" = "/api/nodes/{bundle}"
 *   }
 * )
 */
class NodeListResource extends FormationResourceBase {

  /**
   * Get endpoint.
   */
  public function get($bundle) {
    $nodes = $this->nodeStorage->loadByProperties([
      'type' => $bundle,
    ]);

    $items = [];
    foreach ($nodes as $node) {
      $data = [
        'title' => $node->getTitle(),
//        'body' => $node->get('body')->value,
      ];
      if ($imageMedia = $node->field_image->entity) {
        $imageUri = $imageMedia->field_media_image->entity->getFileUri();
        $style = $this->entityTypeManager
          ->getStorage('image_style')
          ->load('600x600');
        $image = $style->buildUrl($imageUri);
        $data['image'] = $image;
      }
      $items[] = $data;
    }
    return new ResourceResponse($items);
  }

}
