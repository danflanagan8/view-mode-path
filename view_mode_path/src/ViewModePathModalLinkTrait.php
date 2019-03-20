<?php

namespace Drupal\view_mode_path;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

trait ViewModePathModalLinkTrait {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = 'Open as ' . $this->getSetting('view_mode') . ' in modal.';
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();
    $options['view_mode'] = 'default';
    $options['modal_width'] = '750';
    $options['dialog_classes'] = '';

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $elements['view_mode'] = [
      '#type' => 'select',
      '#options' => $this->getViewModeOptions(),
      '#title' => t('View mode'),
      '#default_value' => $this->getSetting('view_mode'),
      '#required' => TRUE,
    ];

    $elements['modal_width'] = [
      '#type' => 'number',
      '#title' => t('Modal Width (px)'),
      '#default_value' => $this->getSetting('modal_width'),
      '#required' => TRUE,
    ];

    $elements['dialog_classes'] = [
      '#type' => 'textfield',
      '#title' => t('Dialog Classes'),
      '#default_value' => $this->getSetting('dialog_classes'),
      '#description' => $this->t('Enter a space-separated list of custom classes to add to the dialog. This formatter always adds default classes like entity type and view mode.'),
      '#required' => FALSE,
    ];

    //so people know this formatter ALWAYS makes links
    if(isset($elements['image_link'])){
      $elements['image_link']['#required'] = TRUE;
    }
    else if(isset($elements['link'])){
      $elements['link']['#required'] = TRUE;
    }
    else if(isset($elements['link_to_entity'])){
      $elements['link_to_entity']['#required'] = TRUE;
    }

    return $elements;
  }

  public static function getModalUrl($entity, $view_mode){
    return view_mode_path_get_url($entity, $view_mode);
  }

  public function getViewModeOptions(){
    //for simplicity's sake, simply always return all view mode options for nodes and media.
    return array_merge(\Drupal::entityManager()->getViewModeOptions('node'), \Drupal::entityManager()->getViewModeOptions('media'));
  }

  public static function getModalAttributes($entity, $settings){
    $classes = [
      Html::cleanCssIdentifier($entity->getEntityTypeId()),
      Html::cleanCssIdentifier($entity->bundle()),
      Html::cleanCssIdentifier($settings['view_mode']),
      isset($settings['dialog_classes']) ? $settings['dialog_classes'] : '',
    ];

    $dialogClass = implode($classes, ' ');

    $dialog_options = [
      'width' => $settings['modal_width'],
      'dialogClass' => $dialogClass,
    ];

    $attributes = array(
      'data-dialog-options' => json_encode($dialog_options),
      'data-dialog-type' => 'modal',
      'class' => array(
        'use-ajax',
      ),
    );
    return $attributes;
  }

}
