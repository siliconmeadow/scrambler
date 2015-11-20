<?php
/**
 * @file
 *
 * Administration page for Scrambler.
 */

use Drupal\scrambler;

function scrambler_administration_page() {
  $page = array(
    '#type' => 'markup',
    '#markup' => t("Scrambler is an API. It doesn't do anything by itself. Some submodules of Scrambler have configuration pages. Enable them in order to see the configuration tabs."),
  );

  return drupal_render($page);
}

function scrambler_administration_execute_page() {
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
    '#submit' => array('scrambler_administration_process_page'),
  );

  return $form;
}

function scrambler_administration_process_page() {
  $batch = array(
    'title' => t('Scrambling data'),
    'init_message' => t('Starting Scrambler.'),
    'operations' => array('scrambler_administration_processor', array()),
    'finished' => 'scrambler_administration_process_page_finished',
  );

  batch_set($batch);
}

function scrambler_administration_processor(&$context) {
  $api = new scrambler\API();
  $api->scramble();

  // @todo: provide feedback with: $context['message'] = t('Now processing %item on %table');
}

function scrambler_administration_process_page_finished() {
  drupal_set_message(t('Scramble finished.'));
}
