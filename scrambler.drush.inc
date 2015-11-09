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
  );

  return $items;
}

/**
 * Executes the scramble data command.
 */
function drush_scrambler_scramble() {
  $drush = new \Drupal\scrambler\Drush\Drush;
  $drush->execute();
}
