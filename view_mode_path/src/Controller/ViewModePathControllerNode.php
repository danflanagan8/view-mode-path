<?php

namespace Drupal\view_mode_path\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ViewModePathController
 */

class ViewModePathControllerNode extends ControllerBase {

  public function build($id = NULL, $view_mode = 'default'){
    if ($id && $node = Node::load($id)) {
      if ($node->access('view', $this->currentUser())) {
        return \Drupal::entityTypeManager()->getViewBuilder('node')->view($node, $view_mode);
      }
      else {
        throw new AccessDeniedHttpException();
      }
    }
    else {
      throw new NotFoundHttpException();
    }
  }

  public function getTitle($id = NULL, $view_mode = 'default'){
    if ($id && $node = Node::load($id)) {
      return $node->label();
    }
  }

}
