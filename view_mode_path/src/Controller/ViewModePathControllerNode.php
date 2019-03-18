<?php

namespace Drupal\view_mode_path\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class ViewModePathController
 */

class ViewModePathControllerNode extends ControllerBase {

  public function build($id = NULL, $view_mode = 'default'){
    if ($id && $node = Node::load($id)) {
      return \Drupal::entityTypeManager()->getViewBuilder('node')->view($node, $view_mode);
    }
    else {
      return NULL;
    }
  }

  public function getTitle($id = NULL, $view_mode = 'default'){
    if ($id && $node = Node::load($id)) {
      return $node->label();
    }
  }

}
