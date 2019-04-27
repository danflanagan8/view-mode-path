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
    $defaults = \Drupal::config('view_mode_path.settings');
    if ($this->getSetting('view_mode')) {
      $summary[] = 'Open as ' . $this->getSetting('view_mode') . ' in modal.';
    }
    else {
      $summary[] = 'Open as ' . $defaults->get('view_mode') . ' in modal.';
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();
    $options['view_mode'] = '';
    $options['modal_width'] = '';
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
      '#empty_option' => 'Use value from view_mode_path.settings',
      '#options' => $this->getViewModeOptions(),
      '#title' => t('View mode'),
      '#description' => t('If left blank, the value from view_mode_path.settings will be used.'),
      '#default_value' => $this->getSetting('view_mode'),
      '#required' => FALSE,
    ];

    $elements['modal_width'] = [
      '#type' => 'textfield',
      '#title' => t('Modal Width'),
      '#description' => t('If left blank, the value from view_mode_path.settings will be used. Enter any value for a css width.'),
      '#default_value' => $this->getSetting('modal_width'),
      '#required' => FALSE,
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

  public static function getModalUrl($entity, $view_mode = NULL){
    return view_mode_path_get_url($entity, $view_mode);
  }

  public function getViewModeOptions(){
    //for simplicity's sake, simply always return all view mode options for nodes and media.
    return array_merge(\Drupal::entityManager()->getViewModeOptions('node'), \Drupal::entityManager()->getViewModeOptions('media'));
  }

  public static function getModalAttributes($entity, array $settings){

    $defaults = \Drupal::config('view_mode_path.settings');
    if(empty($settings['view_mode'])){
      $settings['view_mode'] = $defaults->get('view_mode');
    }
    if(empty($settings['modal_width'])){
      $settings['modal_width'] = $defaults->get('modal_width');
    }

    $classes = [
      $entity ? Html::cleanCssIdentifier($entity->getEntityTypeId()) : 'no-entity',
      $entity ? Html::cleanCssIdentifier($entity->bundle()) : 'no-bundle',
      Html::cleanCssIdentifier($settings['view_mode']),
    ];

    if ($settings['dialog_classes']) {
      $classes[] = $settings['dialog_classes'];
    }

    $dialogClass = implode($classes, ' ');

    $dialog_options = [
      'width' => $settings['modal_width'],
      'dialogClass' => $dialogClass,
    ];

    $attributes = array(
      'data-dialog-options' => json_encode($dialog_options),
      'data-dialog-type' => 'modal',
      'class' => array(
        'view-mode-path-modal-link',
      ),
    );
    return $attributes;
  }

}
