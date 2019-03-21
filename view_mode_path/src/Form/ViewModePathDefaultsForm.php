<?php

namespace Drupal\view_mode_path\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure default optinos for View Mode Path
 */
class ViewModePathDefaultsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'view_mode_path_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'view_mode_path.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('view_mode_path.settings');

    $form['view_mode'] = [
      '#type' => 'textfield',
      '#title' => 'Defaut View Mode in Modals',
      '#description' => 'When using View Mode Path to open node/media in a modal, this is the default view mode.',
      '#default_value' => !empty($config->get('view_mode')) ? $config->get('view_mode') : 'teaser',
      '#required' => TRUE,
    ];

    $form['modal_width'] = [
      '#type' => 'textfield',
      '#title' => 'Modal Width',
      '#description' => 'Enter any value that can be used for width in css. If you enter a number, the unit is assumed to be px.',
      '#default_value' => !empty($config->get('modal_width')) ? $config->get('modal_width') : '80%',
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('view_mode_path.settings');
    $config->set('view_mode', $form_state->getValue(['view_mode']));
    $config->set('modal_width', $form_state->getValue(['modal_width']));
    $config->save();

    parent::submitForm($form, $form_state);
  }
}
