<?php
/**
 * @file
 * Contains the default scrambler scramble methods.
 */
// Define method constants.
define('SCRAMBLER_METHOD_SHUFFLE_CHARACTERS', 'shuffle_characters');
define('SCRAMBLER_METHOD_SHUFFLE_WORDS', 'shuffle_words');
define('SCRAMBLER_METHOD_EMPTY_VALUE', 'empty_value');
define('SCRAMBLER_METHOD_REPLACE_RANDOM', 'replace_random');
define('SCRAMBLER_METHOD_SCRAMBLE_EMAIL', 'scramble_email');
define('SCRAMBLER_METHOD_SCRAMBLE_URL', 'scramble_url');

/**
 * Implements hook_scrambler_methods().
 */
function scrambler_scrambler_methods() {
  return array(
    SCRAMBLER_METHOD_SHUFFLE_CHARACTERS => 'Shuffle characters',
    SCRAMBLER_METHOD_SHUFFLE_WORDS => 'Shuffle words',
    SCRAMBLER_METHOD_EMPTY_VALUE => 'Empty values',
    SCRAMBLER_METHOD_REPLACE_RANDOM => 'Replace with random characters',
    SCRAMBLER_METHOD_SCRAMBLE_EMAIL => 'Scramble an e-mail',
    SCRAMBLER_METHOD_SCRAMBLE_URL => 'Scramble an url',
  );
}

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
  $data = substr(md5(microtime()), rand(0, 26), $data_length);
}

/**
 * Scramble the characters of an e-mail and maintain the same e-mail length.
 *
 * @param mixed $data
 *   Expects an e-mail.
 */
function _scrambler_method_scramble_email(&$data) {
  if (valid_email_address($data)) {
    $arr_data = explode('@', $data);
    _scrambler_method_shuffle_characters($arr_data[0]);
    $arr_dot_data = explode('.', $arr_data[1]);
    _scrambler_method_shuffle_characters($arr_dot_data[0]);
    _scrambler_method_shuffle_characters($arr_dot_data[1]);
    $arr_data[1] = implode('.', $arr_dot_data);
    $data = implode('@', $arr_data);
  }
  else {
    _scrambler_method_shuffle_characters($data);
  }
}

/**
 * Scramble the characters of an url and retain the same url length.
 *
 * @param mixed $data
 *   Expects an url.
 */
function _scrambler_method_scramble_url(&$data) {
  if (valid_url($data)) {
    $parsed = parse_url($data);
    if (isset($parsed['host'])) {
      _scrambler_method_shuffle_characters($parsed['host']);
    }
    if (isset($parsed['path'])) {
      _scrambler_method_shuffle_characters($parsed['path']);
    }
    if (isset($parsed['query'])) {
      _scrambler_method_shuffle_characters($parsed['query']);
    }
    $data = _scrambler_method_helper_unparse_url($parsed);
  }
  else {
    _scrambler_method_shuffle_characters($data);
  }
}

/**
 * Helper function to unparse a parsed url.
 *
 * @param array $parsed
 *   Parsed url array.
 * @return string
 *   Returns an unparsed url.
 */
function _scrambler_method_helper_unparse_url(array $parsed) {
  $pass = _scrambler_method_helper_parsed_value($parsed, 'pass');
  $userinfo = _scrambler_method_helper_parsed_value($parsed, 'user') .
    (strlen($pass) ? ":$pass" : '');
  $port = _scrambler_method_helper_parsed_value($parsed, 'port');
  $scheme = _scrambler_method_helper_parsed_value($parsed, 'scheme');
  $query = _scrambler_method_helper_parsed_value($parsed, 'query');
  $fragment = _scrambler_method_helper_parsed_value($parsed, 'fragment');
  $authority = (strlen($userinfo) ? "$userinfo@" : '') .
    _scrambler_method_helper_parsed_value($parsed, 'host') .
    ($port ? ":$port" : '');

  return (strlen($scheme) ? "$scheme:" : '') .
    (strlen($authority) ? "//$authority" : '') .
    _scrambler_method_helper_parsed_value($parsed, 'path') .
    (strlen($query) ? "?$query" : '') .
    (strlen($fragment) ? "#$fragment" : '');
}

/**
 * Returns the key if exists else it returns NULL.
 *
 * @param array $parsed
 *   Contains an array of parsed values of an url.
 * @param string $key
 *   Contains the key to search for in the array.
 *
 * @return string|NULL
 *   Returns the key value of the array or NULL if the key not exists.
 */
function _scrambler_method_helper_parsed_value($parsed, $key) {
  return isset($parsed[$key]) ? $parsed[$key] : NULL;
}
