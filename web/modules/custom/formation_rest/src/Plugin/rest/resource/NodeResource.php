<?php

namespace Drupal\formation_rest\Plugin\rest\resource;

use Drupal\rest\ResourceResponse;

/**
 * Class Transaction to return the response external.
 *
 * @RestResource(
 *   id = "formation_node",
 *   label = @Translation("Node by nid"),
 *   uri_paths = {
 *     "canonical" = "/api/node/{nid}"
 *   }
 * )
 */
class NodeResource extends FormationResourceBase {

  /**
   * Get endpoint.
   */
  public function get($nid) {
    $node = $this->nodeStorage->loadByProperties([
      'nid' => $nid,
    ]);
    $node = reset($node);

    $data = [
      'title' => $node->getTitle(),
      'body' => $node->get('body')->value,
    ];
    if ($imageMedia = $node->field_image->entity) {
      $imageUri = $imageMedia->field_media_image->entity->getFileUri();
      $style = $this->entityTypeManager
        ->getStorage('image_style')
        ->load('medium');
      $image = $style->buildUrl($imageUri);
      $data['image'] = $image;
    }
    return new ResourceResponse($data);
  }

}
