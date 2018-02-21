<?php
/**
 * @file
 * Drush class for the scrambler module.
 */

namespace Drupal\scrambler\Drush;

use Drupal\scrambler\API;

/**
 * Drush class for the scrambler module.
 */
class Drush {

  /**
   * Contains the API object.
   *
   * @var \Drupal\scrambler\API;
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
  public function execute($option=NULL) {
    // Assuming you want to continue with a one-liner in your shell.
    // This is useful for CI tools.
    if ($option === 'i-understand-the-risks') {
      drush_log(dt('Start scrambling fields (option: i-understand-the-risks).'), 'ok');
      $this->api->scramble();
      drush_log(dt('Successfully scrambled fields.'), 'ok');
    }

    // Prompt the user with a question.
    elseif ($this->proceed()) {
      drush_log(dt('Start scrambling fields.'), 'ok');
      $this->api->scramble();
      drush_log(dt('Successfully scrambled fields.'), 'ok');
    }
  }

  /**
   * Acknowledge to proceed.
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
