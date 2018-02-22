<?php
/**
 * @file
 * Contains administration functionality for scrambler fields.
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
function scrambler_field_settings_form(array $form, array &$form_state) {

  $menu_entries = array(
    'items' => array(
      l(t('General settings'), 'admin/config/development/scrambler/general'),
      l(t('Field settings'), 'admin/config/development/scrambler/fields'),
    ),
    'type' => 'ul',
    'title' => t('Configure the scrambling process'),
    'attributes' => array(),
  );

  $form['menu'] = array(
    '#type' => 'markup',
    '#markup' => theme('item_list', array('items' => $menu_entries)),
  );

  return $form;
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
function scrambler_field_fields_form(array $form, array &$form_state) {
  if (_scrambler_field_show_fields_form()) {
    return array();
  }

  $scramble_controller = new \Drupal\scrambler\Controller\ScrambleController();
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
      '#description' => t('Select the field you want to scramble. File fields are removed from this selection.'),
      '#options' => $fields,
      '#required' => TRUE,
    );
    $form['scrambler_fields']['scrambler_method'] = array(
      '#type' => 'select',
      '#title' => t('Method'),
      '#description' => t('Select a scramble method for the selected field.'),
      '#options' => $scramble_controller->getMethods(),
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
function scrambler_field_fields_form_submit(array $form, array &$form_state) {
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
    $entity->field_name = $fields[$entity->fid];
    $entity->scrambler_method = $form_state['values']['scrambler_method'];
    $entity->save();
  }
}

/**
 * Helper function to verify if the fields form should be shown.
 *
 * @return bool
 *   Returns TRUE or FALSE.
 */
function _scrambler_field_show_fields_form() {
  return (bool) (
    isset($_POST['operation']) &&
    isset($_POST['views_bulk_operations']) &&
    ($_POST['operation'] == 'action::views_bulk_operations_delete_item')
  );
}
