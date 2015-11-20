<?php
/**
 * @file
 * Contains the default scrambler scramble methods.
 */

// Define method constants.
DEFINE('SCRAMBLER_METHOD_SHUFFLE_CHARACTERS', 'shuffle_characters');
DEFINE('SCRAMBLER_METHOD_SHUFFLE_WORDS', 'shuffle_words');
DEFINE('SCRAMBLER_METHOD_EMPTY_VALUE', 'empty_value');

/**
 * Shuffle characters.
 *
 * @param mixed $data
 *   Expects any kind of data.
 */
function _scrambler_method_shuffle_characters(&$data) {
  if (is_string($data)) {
    $data = str_shuffle($data);
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
    $words = explode(' ', $data);
    $data = implode(' ', shuffle($words));
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
