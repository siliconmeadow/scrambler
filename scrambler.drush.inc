<?php
/**
 * @file
 * Scrambler - Contains all drush commands.
 */

/**
 * Implements hook_drush_command().
 */
function scrambler_drush_command() {

  $items['scramble'] = array(
    'description' => 'Scramble data.',
    'aliases' => array('scr-d'),
    'arguments' => array(
      'i-understand-the-risks' => 'Acknowledge you are sure! Useful when running a CI tool',
    ),
  );

  return $items;
}

/**
 * Executes the scramble data command.
 */
function drush_scrambler_scramble($option=NULL) {
  $drush = new \Drupal\scrambler\Drush\Drush();
  $drush->execute($option);
}
