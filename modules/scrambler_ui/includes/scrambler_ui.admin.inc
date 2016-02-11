<?php
/**
 * @file
 * Administration page for Scrambler.
 */

use Drupal\scrambler;

/**
 * Provide the page that allows you to execute scrambler.
 *
 * @return array
 *   Return the form.
 */
function scrambler_ui_administration_execute_page() {
  $form['title'] = array(
    '#type' => 'markup',
    '#markup' => "<h2>" . t('Scrambler execute') . "</h2>",
  );

  $form['warning'] = array(
    '#type' => 'markup',
    '#markup' => t('Do not execute this operation on your production database!'),
  );

  $form['actions'] = array('#type' => 'actions');

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Start scrambling'),
    '#submit' => array('scrambler_ui_administration_process_page'),
  );

  return $form;
}

/**
 * Set the batch assignment for Drupal.
 */
function scrambler_ui_administration_process_page() {

  $batch = array(
    'title' => t('Scrambling data'),
    'init_message' => t('Starting Scrambler.'),
    'error_message' => t('An error occurred'),
    'operations' => array(),
    'finished' => 'scrambler_ui_administration_process_page_finished',
  );

  $batch['operations'][] = array('scrambler_ui_administration_processor', array('all'));

  batch_set($batch);
}

/**
 * Processor for the actual scrambling.
 *
 * @param string $selector
 *   Selector.
 * @param array $context
 *   Drupal's context for batch processing.
 */
function scrambler_ui_administration_processor($selector, &$context) {
  $api = new scrambler\API();
  $api->scramble();
  $context['results']['processed']++;
  $context['message'] = t('Scramble process active.');

  // @todo: provide feedback with: $context['message'] = t('Now processing %item on %table');
}

/**
 * Provide feedback after the scrambling is done.
 */
function scrambler_administration_process_page_finished() {
  drupal_set_message(t('Scramble finished.'));
}
