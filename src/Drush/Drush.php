<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of drush
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */

namespace Drupal\scrambler\Drush;

use Drupal\scrambler\BLL\API;

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
      foreach(module_implements('scrambler_api') as $module) {
        $function = $module . '_scrambler_api';
        $success = $function($this->api);
      }
      if ($this->api->scramble() == TRUE) {
        drush_log(dt('Successfully scrambled.'), $type = 'ok');
      }
      else {
        drush_log(dt('Something went wrong.'), $type = 'error');
      }
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
      array(TRUE => 'Yes'),
      'Are you sure to scramble the database? Be sure that you are not executing it on the production database.'
    );
  }
}
