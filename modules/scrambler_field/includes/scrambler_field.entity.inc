<?php
/**
 * @file
 * Entity information/configuration of the Scrambler module.
 */

/**
 * Implements hook_entity_info().
 */
function scrambler_field_entity_info() {
  return array(
    'scrambler_field' => array(
      'base table' => 'scrambler_field',
      'controller class' => 'EntityAPIController',
      'entity class' => 'Entity',
      'entity keys' => array(
        'id' => 'fid',
      ),
      'label' => t('scrambler field'),
      'label callback' => 'scrambler_field_entity_label_callback',
      'module' => 'scrambler',
      'views controller class' => 'EntityDefaultViewsController',
    )
  );
}

/**
 * Entity label callback function.
 *
 * @param \Entity $entity
 *   Contains the scrambler_field entity object.
 * @param string $type
 *   Contains the type of the entity.
 *
 * @return string
 *   Returns the field name.
 */
function scrambler_field_entity_label_callback($entity, $type) {
  if ($type != 'scrambler_field') {
    return FALSE;
  }

  if ($entity->fid == -1) {
    return 'title';
  }
  else {
    $field = field_info_field_by_id($entity->fid);

    return empty($field['field_name']) ? t('Unknown field_name') : $field['field_name'];
  }
}

/**
 * Implements hook_entity_property_info().
 */
function scrambler_field_entity_property_info() {
  return array(
    'scrambler_field' => array(
      'properties' => array(
        'field_name' => array(
          'type' => 'text',
          'label' => t('Field name'),
          'description' => t('Field name by fid'),
          'schema field' => 'fid',
        ),
      ),
    ),
  );
}
