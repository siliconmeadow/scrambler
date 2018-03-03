<?php
/**
 * @file
 * Contains administration form functionality to the scrambler comment module.
 */

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
function scrambler_field_administer_comments_form(array $form, array &$form_state) {
  $scrambler_comments = variable_get('scrambler_comments', array());

  $form['scrambler_comments'] = array(
    '#type' => 'fieldset',
    '#title' => t('Configure scrambling for comment data.'),
    '#tree' => TRUE,
  );
  $form['scrambler_comments']['name'] = array(
    '#type' => 'checkbox',
    '#title' => t('Name'),
    '#description' => t("The commenter's name will be scrambled."),
    '#default_value' => isset($scrambler_comments['name']) ? $scrambler_comments['name'] : NULL,
  );
  $form['scrambler_comments']['mail'] = array(
    '#type' => 'checkbox',
    '#title' => t("Commenter's email address"),
    '#description' => t("Scramble the commenter's email address."),
    '#default_value' => isset($scrambler_comments['mail']) ? $scrambler_comments['mail'] : NULL,
  );
  $form['scrambler_comments']['homepage'] = array(
    '#type' => 'checkbox',
    '#title' => t("The commenter's homepage"),
    '#description' => t("This will scramble the URL of the homepage the user has entered, if it exists."),
    '#default_value' => isset($scrambler_comments['homepage']) ? $scrambler_comments['homepage'] : NULL,
  );

  return system_settings_form($form);
}
