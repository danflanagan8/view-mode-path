<?php

namespace Drupal\view_mode_path\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\view_mode_path\ViewModePathModalLinkTrait;


/**
 *
 * @FieldFormatter(
 *   id = "view_mode_path_label",
 *   label = @Translation("Label Modal Link"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class ViewModePathLabel extends EntityReferenceLabelFormatter {

  use ViewModePathModalLinkTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $label = $entity->label();
      $url = $this->getModalUrl($entity);
      $elements[$delta] = [
        '#type' => 'link',
        '#title' => $label,
        '#url' => $url,
        '#options' => [
          'attributes' => [
            'data-dialog-options' => '{"width":' . $this->getSetting('modal_width') . '}',
            'data-dialog-type' => 'modal',
            'class' => array(
              'use-ajax',
            ),
          ],
        ],
      ];

      $elements[$delta]['#cache']['tags'] = $entity->getCacheTags();
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    // This formatter is only available for references to nodes or media
    // Other entities don't get routes from this module.
    $target_type = $field_definition->getFieldStorageDefinition()->getSetting('target_type');

    return ($target_type == 'media' || $target_type == 'node');
  }

}
