<?php
/**
 * @file
 * Contains the API module functions for the scrambler module.
 */

/**
 * Declaration of hook_scrambler_api().
 *
 * @param array $params
 *   Expects an array with parameters for the scrambling procedures.
 *
 * @return array
 *   Returns the parameters array.
 */
function hook_scrambler_api(&$params) {
  return $params;
  // Currently nothing is required in this hook.
}

/**
 * Declaration of hook_scrambler_methods.
 */
function hook_scrambler_methods() {
  // Currently no action is required in this hook.
}