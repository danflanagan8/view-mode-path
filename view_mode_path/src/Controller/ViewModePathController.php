<?php

namespace Drupal\view_mode_path\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class ViewModePathController
 */

class ViewModePathController extends ControllerBase {

  public function build($nid = NULL, $view_mode = 'default'){
    if ($nid && $node = Node::load($nid)) {
      return \Drupal::entityTypeManager()->getViewBuilder('node')->view($node, $view_mode);
    }
    else {
      return NULL;
    }
  }

  public function getTitle($nid = NULL, $view_mode = 'default'){
    if ($nid && $node = Node::load($nid)) {
      return $node->getTitle();
    }
  }

}
