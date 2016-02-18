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
  $api = new scrambler\API();
  $batch_items = $api->scrambleBatchGroups();

  $batch = array(
    'title' => t('Scrambling data'),
    'init_message' => t('Depending on the amount, scrambling might be a lengthy and resourceful batch.'),
    'error_message' => t('An error occurred'),
    'operations' => array(),
    'finished' => 'scrambler_ui_administration_process_page_finished',
    'file' => drupal_get_path('module', 'scrambler_ui') . '/includes/scrambler_ui.admin.inc',
  );

  foreach ($batch_items as $batch_item) {
    foreach ($batch_item as $module => $group) {
      $batch['operations'][] = array(
        'scrambler_ui_administration_processor',
        array($module, $group),
      );
    }

  }

  $batch['progressive'] = TRUE;

  batch_set($batch);
}

/**
 * Processor for the actual scrambling.
 *
 * @param string $module
 *   Module name of implementation.
 * @param string $group
 *   Group name of implementation.
 * @param array $context
 *   Drupal's context for batch processing.
 */
function scrambler_ui_administration_processor($module, $group, &$context) {
  $api = new scrambler\API();
  $object = $api->prepareScramblerObject($module, $group);
  $object->execute();

  $data = (object) $group;

  $context['results']['processed']++;
  $context['message'] = t('Processing @module on @table: @field (@method)', array(
    '@module' => $data->module,
    '@table' => $data->base_table,
    '@field' => $data->fields[0],
    '@method' => $data->method,
  )
  );
}

/**
 * Provide feedback after the scrambling is done.
 */
function scrambler_administration_process_page_finished($success, $results, $operations) {
  if ($success) {
    // Empty all caches.
    drupal_flush_all_caches();
    // Set feedback for the user.
    drupal_set_message(t('Scramble finished.'));
  }
  else {
    // Display last operation message as an error message for the user.
    $error_operation = reset($operations);
    $message = t('An error occurred while processing %error_operation with arguments: @arguments', array(
      '%error_operation' => $error_operation[0],
      '@arguments' => print_r($error_operation[1], TRUE),
    ));
    drupal_set_message($message, 'error');
  }
}
