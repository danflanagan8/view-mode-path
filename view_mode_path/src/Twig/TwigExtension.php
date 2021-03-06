<?php

namespace Drupal\view_mode_path\Twig;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Template\Attribute;
use Drupal\view_mode_path\ViewModePathModalLinkTrait;

class TwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('view_mode_path_modal_link_attributes', [$this, 'modalLinkAttributes']),
      new \Twig_SimpleFunction('view_mode_path_modal_link_by_route_attributes', [$this, 'modalLinkByRouteAttributes']),
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
  public function modalLinkAttributes(EntityInterface $entity, string $view_mode = NULL, string $modal_width = NULL, string $anchor_classes = NULL, string $dialog_classes = NULL) {
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
    //attach a library just like in core's attach_library twig function
    $template_attached = ['#attached' => ['library' => ['view_mode_path/view-mode-path-modal-link']]];
    \Drupal::service('renderer')->render($template_attached);

    //now return the main event
    return new Attribute($attributes);
  }

  /**
   *
   */
  public function modalLinkByRouteAttributes(string $route, $parameters = [], string $modal_width = NULL, string $anchor_classes = NULL, string $dialog_classes = NULL) {
    $settings = [
      'view_mode' => NULL,
      'modal_width' => $modal_width,
      'dialog_classes' => $dialog_classes,
    ];
    $attributes = ViewModePathModalLinkTrait::getModalAttributes(NULL, $settings);
    $attributes['href'] = \Drupal::urlGenerator()->generateFromRoute($route, $parameters, ['absolute' => FALSE]);

    //add to the class attribute
    if($anchor_classes){
      $attributes['class'][] = $anchor_classes;
    }
    //attach a library just like in core's attach_library twig function
    $template_attached = ['#attached' => ['library' => ['view_mode_path/view-mode-path-modal-link']]];
    \Drupal::service('renderer')->render($template_attached);

    //now return the main event
    return new Attribute($attributes);
  }

}
