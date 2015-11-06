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
      $this->api->scramble();
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
