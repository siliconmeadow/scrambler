<?php
/**
 * @file
 * Contains the default scrambler scramble methods.
 */

// Define method constants.
define('SCRAMBLER_METHOD_SHUFFLE_CHARACTERS', 'shuffle_characters');
define('SCRAMBLER_METHOD_SHUFFLE_WORDS', 'shuffle_words');
define('SCRAMBLER_METHOD_EMPTY_VALUE', 'empty_value');

/**
 * Shuffle characters.
 *
 * @param mixed $data
 *   Expects any kind of data.
 */
function _scrambler_method_shuffle_characters(&$data) {
  if (is_string($data)) {
    // Keep original value in order to validate after shuffling.
    $original = $data;
    // Check to see if data has actually become different from the original.
    while ($original == $data) {
      $data = str_shuffle($data);
    }
  }
}

/**
 * Shuffle words.
 *
 * @param mixed $data
 *   Expects any kind of data.
 */
function _scrambler_method_shuffle_words(&$data) {
  if (is_string($data)) {
    $words = $original = explode(' ', $data);
    while ($original == $words) {
      shuffle($words);
    }
    $data = implode(' ', $words);
  }
}

/**
 * Empty value.
 *
 * @param mixed $data
 *   Expects any kind of data.
 */
function _scrambler_method_empty_value(&$data) {
  if (is_string($data)) {
    $data = '';
  }
}
