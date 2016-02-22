<?php
/**
 * @file
 * Contains Variable configuration for the scrambler field module.
 */

namespace Drupal\scrambler_field\Config;

/**
 * Variable class.
 */
class Variable {

  /**
   * Get general settings.
   *
   * @return array
   *   Returns the general scrambler settings array.
   */
  private function getGeneralSettings() {
    return variable_get('scrambler_general', array());
  }

  /**
   * Get the title from the settings.
   *
   * @return int
   *   Returns 0 or 1.
   */
  public function getTitle() {
    $general = $this->getGeneralSettings();

    if (array_key_exists('title', $general)) {
      return $general['title'];
    }

    return 0;
  }

  /**
   * Get the content types.
   *
   * @return array
   *   Returns an array of content types.
   */
  public function getContentTypes() {
    $general = $this->getGeneralSettings();

    if (array_key_exists('content_types', $general)) {
      return $general['content_types'];
    }

    return array();
  }

}
