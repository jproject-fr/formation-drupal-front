<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class PatternsController extends ControllerBase {
  public function content() {
    return [
      '#theme' => 'hello_patterns',
    ];
  }
}
