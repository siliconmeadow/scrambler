<?php
/**
 * @file
 * Drush class for the scrambler module.
 */

namespace Drupal\scrambler\Drush;

use Drupal\scrambler\BLL\API;

/**
 * Drush class for the scrambler module.
 */
class Drush {

  /**
   * @var \Drupal\scrambler\BLL\API;
   */
  private $api;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->api = new API();
  }

  /**
   * Acknowledge execution and start scrambling.
   */
  public function execute() {
    if ($this->proceed()) {
      $params = array();
      foreach (module_implements('scrambler_api') as $module) {
        $function = $module . '_scrambler_api';
        $function($params);
      }
      foreach ($params as $param) {
        // @todo: Add execution per given parameter data array.
      }
      drush_log(dt('Successfully scrambled fields.'), $type = 'ok');
    }
  }

  /**
   * Acknowlodge to proceed.
   *
   * @return bool
   *   Returns TRUE if choice is yes.
   */
  private function proceed() {
    return drush_choice(
            array(TRUE => 'Yes'), 'Are you sure to scramble the database? Be sure that you are not executing it on the production database.'
    );
  }
}
