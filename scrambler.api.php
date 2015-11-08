<?php
/**
 * @file
 *
 * API model for Scrambler.
 */

/**
 * Declaration of hook_scrambler_api().
 *
 * @param \Drupal\scrambler\BLL\API $api
 *   Contains the api class object.
 *
 * @return bool
 *   Returns always TRUE.
 */
function hook_scrambler_api(&$api) {
  return TRUE;
}
