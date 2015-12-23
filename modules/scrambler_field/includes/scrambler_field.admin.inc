<?php
/**
 * @file
 *   Contains administration functionality for scrambler fields.
 */

/**
 * Generates the administer field page content.
 *
 * @return string
 *   Returns rendered content.
 */
function scrambler_field_administer_fields_page() {
  $form = drupal_get_form('scrambler_field_fields_form');
  return render($form) . views_embed_view('scrambler_fields_administrative_view', 'block');
}

/**
 * Generates the settings form.
 *
 * @param array $form
 *   The form object to be generated.
 * @param array $form_state
 *   The form state of the form object to be generated.
 *
 * @return array
 *   Returns the generated form object.
 */
function scrambler_field_settings_form($form, &$form_state) {

  $menu_entries = array(
    'items' => array(
      l('General settings', 'admin/config/development/scrambler/general'),
      l('Field settings', 'admin/config/development/scrambler/fields'),
    ),
    'type' => 'ul',
    'title' => t('Configure the scrambling process'),
    'attributes' => array(),
  );

  $form['menu'] = array(
    '#type' => 'markup',
    '#markup' => theme_item_list($menu_entries),
  );

  return $form;
}

/**
 * Generates the general settings form.
 *
 * @param array $form
 *   The form object to be generated.
 * @param array $form_state
 *   The form state of the form object to be generated.
 *
 * @return array
 *   Returns the generated form object.
 */
function scrambler_field_general_settings_form($form, &$form_state) {
  $variable = new \Drupal\scrambler_field\Config\Variable();
  $scrambler = new \Drupal\scrambler_field\BLL\Scramble();

  $form['scrambler_general'] = array(
    '#type' => 'fieldset',
    '#title' => t('General settings'),
    '#tree' => TRUE,
  );
  $form['scrambler_general']['title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Scramble title'),
    '#default_value' => $variable->getTitle(),
  );
  $form['scrambler_general']['content_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Scramble content types'),
    '#options' => $scrambler->getContentTypes(),
    '#default_value' => $variable->getContentTypes(),
  );

  return system_settings_form($form);
}

/**
 * Contains the scrambler_fields_form structure.
 *
 * @param array $form
 *   The form object to be generated.
 * @param array $form_state
 *   The form state of the form object to be generated.
 *
 * @return array
 *   Returns the generated form object.
 */
function scrambler_field_fields_form($form, &$form_state) {
  if (
    array_key_exists('form_id', $_POST) &&
    ($_POST['form_id'] != 'scrambler_field_fields_form')
  ) {
    return array();
  }

  $scrambleController = new \Drupal\scrambler\Controller\ScrambleController();
  $scramble = new \Drupal\scrambler_field\BLL\Scramble();
  $fields = $scramble->getScramblerFields(FALSE);

  $form['scrambler_fields'] = array(
    '#type' => 'fieldset',
    '#title' => t('Scramble fields'),
  );

  if ($fields) {
    $form['scrambler_fields']['scrambler_field'] = array(
      '#type' => 'select',
      '#title' => t('Field'),
      '#description' => t('Select the field you want to scramble.'),
      '#options' => $fields,
      '#required' => TRUE,
    );
    $form['scrambler_fields']['scrambler_method'] = array(
      '#type' => 'radios',
      '#title' => t('Method'),
      '#description' => t('Select a scramble method for the selected field.'),
      '#options' => $scrambleController->getMethods(),
      '#required' => TRUE,
    );
    $form['scrambler_fields']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Add field'),
    );
  }
  else {
    $form['scrambler_fields']['information'] = array(
      '#markup' => t('All the available fields are added to the scrambler.'),
    );
  }

  return $form;
}

/**
 * Submit of the scrambler_fields_form.
 *
 * @param array $form
 *   The form object to be submitted.
 * @param array $form_state
 *   The form state of the form object to be submitted.
 */
function scrambler_field_fields_form_submit($form, &$form_state) {
  $scramble = new \Drupal\scrambler_field\BLL\Scramble();
  $fields = $scramble->getScramblerFields();
  if (array_key_exists($form_state['values']['scrambler_field'], $fields)) {
    drupal_set_message(
      t(
        'Added %s to scrambler.',
        array('%s' => $fields[$form_state['values']['scrambler_field']])
      )
    );
    $entity = entity_create('scrambler_field', array());
    $entity->fid = $form_state['values']['scrambler_field'];
    $entity->scrambler_method = $form_state['values']['scrambler_method'];
    $entity->save();
  }
}
