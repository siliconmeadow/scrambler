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
    $chars = count_chars($data, 1);
    // If we have exact the same characters, shuffle will not work.
    if (count($chars) == 1) {
      _scrambler_method_replace_random($data);
    }
    else {
      // Keep original value in order to validate after shuffling.
      $original = $data;
      // Check to see if data has actually become different from the original.
      while ($original == $data) {
        $data = str_shuffle($data);
      }
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
    // In case the unique array of words results in only one record.
    if (count(array_unique($words)) == 1) {
      // Then replace the first word to be able to shuffle the sentence.
      $word = $words[0];
      _scrambler_method_replace_random($word);
      $words[0] = $word;
    }
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

/**
 * Replace with random characters and exact same length.
 *
 * @param mixed $data
 *   Expects any kind of data.
 */
function _scrambler_method_replace_random(&$data) {
  $data_length = strlen($data);
  $data = substr(uniqid('', true), -$data_length);
}
