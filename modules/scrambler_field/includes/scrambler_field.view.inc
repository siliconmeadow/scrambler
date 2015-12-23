<?php
/**
 * @file
 * Contains Scrambler view configuration.
 */

/**
 * Implements hook_views_api().
 */
function scrambler_field_views_api() {
  return array(
    'api' => 3.0,
    'path' => drupal_get_path('module', 'HOOK'),
  );
}

/**
 * Implements hook_views_default_views().
 */
function scrambler_field_views_default_views() {
  $view = new view();
  $view->name = 'scrambler_fields_administrative_view';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'scrambler_field';
  $view->human_name = 'Scrambler fields administrative view';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Scrambler fields administrative view';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'fid' => 'fid',
    'scrambler_method' => 'scrambler_method',
  );
  $handler->display->display_options['style_options']['default'] = 'fid';
  $handler->display->display_options['style_options']['info'] = array(
    'fid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'scrambler_method' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* No results behavior: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['empty'] = TRUE;
  $handler->display->display_options['empty']['area']['content'] = 'No fields have been defined yet.';
  $handler->display->display_options['empty']['area']['format'] = 'filtered_html';
  /* Field: Bulk operations: Scrambler field */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'scrambler_field';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '0';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Scrambler field: Field name */
  $handler->display->display_options['fields']['field_name']['id'] = 'field_name';
  $handler->display->display_options['fields']['field_name']['table'] = 'scrambler_field';
  $handler->display->display_options['fields']['field_name']['field'] = 'field_name';
  $handler->display->display_options['fields']['field_name']['label'] = 'Veldnaam';

  /* Display: Block */
  $handler = $view->new_display('block', 'Block', 'block');

  $translatables['scrambler_fields_administrative_view'] = array(
    t('Master'),
    t('Scrambler fields administrative view'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('No fields have been defined yet.'),
    t('Scrambler field'),
    t('- Choose an operation -'),
    t('Veldnaam'),
    t('.'),
    t('Block'),
  );

  $views[$view->name] = $view;
  return $views;
}

/**
 * Render the field name through the views_view_table theme.
 * 
 * @param array $vars
 *   Contains all the variable information of the views_view_table theme.
 */
function scrambler_field_preprocess_views_view_table(&$vars) {
  $scramble = new \Drupal\scrambler_field\BLL\Scramble();
  $fields = $scramble->getScramblerFields();

  foreach ($vars['rows'] as &$row) {
    if ($row['field_name'] != 0) {
      $row['field_name'] = $fields[$row['field_name']];
    }
  }
}
