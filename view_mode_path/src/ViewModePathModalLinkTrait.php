<?php

namespace Drupal\view_mode_path;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

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

  public function getModalUrl($entity){
    if ($entity->getEntityTypeId() == 'node') {
      $route = 'view_mode_path.node';
    }
    else if($entity->getEntityTypeId() == 'media') {
      $route = 'view_mode_path.media';
    }
    else {
      return NULL;
    }
    $url = Url::fromRoute($route, ['id' => $entity->id(), 'view_mode' => $this->getSetting('view_mode')]);
    return $url;
  }

  public function getViewModeOptions(){
    //for simplicity's sake, simply always return all view mode options for nodes and media.
    return array_merge(\Drupal::entityManager()->getViewModeOptions('node'), \Drupal::entityManager()->getViewModeOptions('media'));
  }

}
