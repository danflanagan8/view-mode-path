<?php

namespace Drupal\view_mode_path\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;

/**
 * Class ViewModePathController
 */

class ViewModePathControllerMedia extends ControllerBase {

  public function build($id = NULL, $view_mode = 'default'){
    if ($id && $media = Media::load($id)) {
      return \Drupal::entityTypeManager()->getViewBuilder('media')->view($media, $view_mode);
    }
    else {
      return NULL;
    }
  }

  public function getTitle($id = NULL, $view_mode = 'default'){
    if ($id && $media = Media::load($id)) {
      return $media->label();
    }
  }

}
