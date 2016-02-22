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
  private function getGeneralSettings() {
    return variable_get('scrambler_general', array());
  }
  
  public function getTitle() {
    $general = $this->getGeneralSettings();

    if (array_key_exists('title', $general)) {
      return $general['title'];
    } 

    return 0;
  }

  public function getContentTypes() {
    $general = $this->getGeneralSettings();

    if (array_key_exists('content_types', $general)) {
      return $general['content_types'];
    } 

    return array();
  }
}
