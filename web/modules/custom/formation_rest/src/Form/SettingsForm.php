<?php

namespace Drupal\formation_rest\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm to handle settings params.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'formation_rest_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['formation_rest.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('formation_rest.settings');

    $form['test'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Test'),
      '#default_value' => $config->get('test'),
    ];
    $form['test2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Test2'),
      '#default_value' => $config->get('test2'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Update results.
    $config = $this->config('formation_rest.settings');
    $values = $form_state->getValues();
    foreach ($form_state->getValues() as $key => $value) {
      $config
        ->set($key, $values[$key])
        ->save();
    }
    parent::submitForm($form, $form_state);
  }

}
