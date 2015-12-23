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
      'label callback' => 'scrambler_entity_label_callback',
      'module' => 'scrambler',
    ),
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

  $field = field_info_field_by_id($entity->fid);

  return empty($field['field_name']) ? t('Unknown field_name') : $field['field_name'];
}

/**
 * Implements hook_entity_property_info().
 */
function scrambler_field_entity_property_info() {
  return array(
    'scrambler_field' => array(
      'properties' => array(
        'fid' => array(
          'type'  => 'integer',
          'label' => t('Field id'),
          'description' => t('The field id.'),
          'schema field' => 'fid',
        ),
        'scrambler_method' => array(
          'type' => 'string',
          'label' => t('Method'),
          'description' => t('Scrambling method'),
        ),
        'status' => array(
          'type' => 'integer',
          'label' => t('Status'),
          'description' => t('Enabled or disabled'),
          'schema field' => 'status',
        ),
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
