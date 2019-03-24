<?php

namespace Drupal\view_mode_path\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ViewModePathController
 */

class ViewModePathControllerMedia extends ControllerBase {

  public function build($id = NULL, $view_mode = 'default'){
    if ($id && $media = Media::load($id)) {
      if ($media->access('view', $this->currentUser())) {
        return \Drupal::entityTypeManager()->getViewBuilder('media')->view($media, $view_mode);
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
    if ($id && $media = Media::load($id)) {
      return $media->label();
    }
  }

}
