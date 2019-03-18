<?php

namespace Drupal\view_mode_path\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\media\MediaInterface;
use Drupal\media\Plugin\Field\FieldFormatter\MediaThumbnailFormatter;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\view_mode_path\ViewModePathModalLinkTrait;

/**
 * Plugin implementation of the 'media_thumbnail' formatter.
 *
 * @FieldFormatter(
 *   id = "view_mode_path_media_thumbnail",
 *   label = @Translation("Thumbnail Modal Link"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class ViewModePathMediaThumbnail extends MediaThumbnailFormatter {

  use ViewModePathModalLinkTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $media_items = $this->getEntitiesToView($items, $langcode);

    // Early opt-out if the field is empty.
    if (empty($media_items)) {
      return $elements;
    }

    $image_style_setting = $this->getSetting('image_style');

    /** @var \Drupal\media\MediaInterface[] $media_items */
    foreach ($media_items as $delta => $media) {
      $elements[$delta] = [
        '#theme' => 'view_mode_path_thumbnail_modal',
        '#item' => $media->get('thumbnail')->first(),
        '#item_attributes' => [],
        '#image_style' => $this->getSetting('image_style'),
        '#url' => $this->getMediaThumbnailUrl($media, $items->getEntity()),
        '#attributes' => $this->getModalAttributes($media, $items->getEntity()),
      ];

      // Add cacheability of each item in the field.
      $this->renderer->addCacheableDependency($elements[$delta], $media);
    }

    // Add cacheability of the image style setting.
    if ($this->getSetting('image_link') && ($image_style = $this->imageStyleStorage->load($image_style_setting))) {
      $this->renderer->addCacheableDependency($elements, $image_style);
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  protected function getMediaThumbnailUrl(MediaInterface $media, EntityInterface $entity) {
    $image_link_setting = $this->getSetting('image_link');
    // Check if the formatter involves a link.
    $url = NULL;
    if ($image_link_setting == 'content') {
      if (!$entity->isNew()) {
        $url = $this->getModalUrl($entity);
      }
    }
    elseif ($image_link_setting === 'media') {
      $url = $this->getModalUrl($media);
    }

    return $url;
  }

  public function getModalAttributes($media, $entity){

    $image_link_setting = $this->getSetting('image_link');
    // Check if the formatter involves a link.
    $attributes = NULL;
    if ($image_link_setting == 'content') {
      if (!$entity->isNew()) {
        $attributes = ViewModePathModalLinkTrait::getModalAttributes($entity, $this->getSettings());
      }
    }
    elseif ($image_link_setting === 'media') {
      $attributes = ViewModePathModalLinkTrait::getModalAttributes($media, $this->getSettings());
    }

    return $attributes;
  }

  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    if(!parent::isApplicable($field_definition)){
      return FALSE;
    }
    // This formatter is only available for nodes and media.
    // Other entity types don't have modal routes from this module.
    $target_bundle = $field_definition->getFieldStorageDefinition()->getTargetEntityTypeId();
    return $target_bundle == 'node' || $target_bundle == 'media';
  }


}
