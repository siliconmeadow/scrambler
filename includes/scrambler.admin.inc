<?php
/**
 * @file
 * Administration page for Scrambler.
 */

/**
 * Administration page.
 *
 * @return string
 *   Provide the default administration page.
 */
function scrambler_administration_page() {
  $page = array(
    '#type' => 'markup',
    '#markup' => t("Scrambler is an API. It doesn't do anything by itself. Some submodules of Scrambler have configuration pages. Enable them in order to see the configuration tabs."),
  );

  return drupal_render($page);
}
