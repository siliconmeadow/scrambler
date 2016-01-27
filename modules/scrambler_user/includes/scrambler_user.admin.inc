<?php
/**
 * @file
 * Contains administration form functionalities for the scrambler user module.
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
function scrambler_field_administer_users_form($form, &$form_state) {
  $scrambler_user = variable_get('scrambler_user');
  
  $form['scrambler_user'] = array(
    '#type' => 'fieldset',
    '#title' => t('Configure scrambling for the user data.'),
    '#tree' => TRUE,
  );
  $form['scrambler_user']['username'] = array(
    '#type' => 'checkbox',
    '#title' => t('Username'),    
    '#description' => t('If username is an e-mail it scrambles the e-mail, else the username characters are scrambled.'),
    '#default_value' => isset($scrambler_user['username']) ? $scrambler_user['username'] : NULL,
  );
  $form['scrambler_user']['password'] = array(
    '#type' => 'checkbox',
    '#title' => t('Password'),
    '#description' => t('Scramble the password by randomizing the characters within.'),
    '#default_value' => isset($scrambler_user['password']) ? $scrambler_user['password'] : NULL,
  );
  $form['scrambler_user']['email'] = array(
    '#type' => 'checkbox',
    '#title' => t('E-mail'),
    '#description' => t('Scrambles the e-mail address and keeps a valid e-mail structure.'),
    '#default_value' => isset($scrambler_user['email']) ? $scrambler_user['email'] : NULL,
  );

  return system_settings_form($form);
}