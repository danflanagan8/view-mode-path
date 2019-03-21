<?php

namespace Drupal\view_mode_path\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Cache\Cache;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;

use Drupal\view_mode_path\ViewModePathModalLinkTrait;

/**
 * Plugin implementation of the 'view_mode_path_image' formatter.
 *
 * @FieldFormatter(
 *   id = "view_mode_path_image",
 *   label = @Translation("Image Modal Link"),
 *   field_types = {
 *     "image"
 *   },
 * )
 */
class ViewModePathImage extends ImageFormatter {

  use ViewModePathModalLinkTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $files = $this->getEntitiesToView($items, $langcode);

    // Early opt-out if the field is empty.
    if (empty($files)) {
      return $elements;
    }

    $url = NULL;
    $image_link_setting = !empty($this->getSetting('image_link')) ? $this->getSetting('image_link') : 'content';
    $entity = $items->getEntity();

    $image_style_setting = $this->getSetting('image_style');

    // Collect cache tags to be added for each item in the field.
    $base_cache_tags = [];
    if (!empty($image_style_setting)) {
      $image_style = $this->imageStyleStorage->load($image_style_setting);
      $base_cache_tags = $image_style->getCacheTags();
    }

    foreach ($files as $delta => $file) {
      $cache_contexts = [];
      $cache_tags = Cache::mergeTags($base_cache_tags, $file->getCacheTags());

      // Extract field item attributes for the theme function, and unset them
      // from the $item so that the field template does not re-render them.
      $item = $file->_referringItem;
      $item_attributes = $item->_attributes;
      unset($item->_attributes);

      //get the url.
      if ($image_link_setting == 'content') {
        if (!$entity->isNew()) {
          $url = ViewModePathModalLinkTrait::getModalUrl($entity, $this->getSetting('view_mode'));
        }
      }
      elseif ($image_link_setting === 'file') {
        $url = ViewModePathModalLinkTrait::getModalUrl($file, $this->getSetting('view_mode'));
      }

      $elements[$delta] = [
        '#theme' => 'view_mode_path_image_modal_link',
        '#item' => $item,
        '#item_attributes' => $item_attributes,
        '#image_style' => $image_style_setting,
        '#url' => $url,
        '#attributes' => $this->getModalAttributes($file, $entity),
        '#cache' => [
          'tags' => $cache_tags,
          'contexts' => $cache_contexts,
        ],
        '#attached' => [
          'library' => [
            'view_mode_path/view-mode-path-modal-link',
          ],
        ],
      ];
    }

    return $elements;
  }

  public function getModalAttributes($file, $entity){

    $image_link_setting = !empty($this->getSetting('image_link')) ? $this->getSetting('image_link') : 'content';
    // Check if the formatter involves a link.
    $attributes = NULL;
    if ($image_link_setting == 'content') {
      if (!$entity->isNew()) {
        $attributes = ViewModePathModalLinkTrait::getModalAttributes($entity, $this->getSettings());
      }
    }
    elseif ($image_link_setting === 'file') {
      $attributes = ViewModePathModalLinkTrait::getModalAttributes($file, $this->getSettings());
    }

    return $attributes;
  }

}
