<?php

namespace Drupal\view_mode_path\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\StringFormatter;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\view_mode_path\ViewModePathModalLinkTrait;

/**
 * Plugin implementation of the 'view_mode_path_string' formatter.
 *
 * @FieldFormatter(
 *   id = "view_mode_path_string",
 *   label = @Translation("Modal Link"),
 *   field_types = {
 *     "string",
 *     "uri",
 *   },
 *   quickedit = {
 *     "editor" = "plain_text"
 *   }
 * )
 */
class ViewModePathString extends StringFormatter {

  use ViewModePathModalLinkTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $entity = $items->getEntity();
    $url = $this->getModalUrl($entity);
    foreach ($items as $delta => $item) {
      $view_value = $this->viewValue($item);
      if ($url) {
        $elements[$delta] = [
          '#type' => 'link',
          '#title' => $view_value,
          '#url' => $url,
          '#options' => [
            'attributes' => ViewModePathModalLinkTrait::getModalAttributes($entity, $this->getSettings()),
          ],
        ];
      }
      else {
        $elements[$delta] = $view_value;
      }
    }
    return $elements;
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
