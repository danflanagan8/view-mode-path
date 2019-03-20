<?php

namespace Drupal\view_mode_path;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Template\Attribute;

class TwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('view_mode_path_modal_link_attributes', [$this, 'modalLinkAttributes']),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'view_mode_path';
  }

  /**
   *
   */
  public function modalLinkAttributes(EntityInterface $entity, string $view_mode = 'default', string $modal_width = '750', string $anchor_classes = NULL, string $dialog_classes = NULL) {
    $settings = [
      'view_mode' => $view_mode,
      'modal_width' => $modal_width,
      'dialog_classes' => $dialog_classes,
    ];
    $attributes = ViewModePathModalLinkTrait::getModalAttributes($entity, $settings);
    $attributes['href'] = ViewModePathModalLinkTrait::getModalUrl($entity, $view_mode)->toString();

    //add to the class attribute
    if($anchor_classes){
      $attributes['class'][] = $anchor_classes;
    }
    return new Attribute($attributes);
  }

}
