<?php
/**
 * @file
 * Creates contract for methods implemented in EntityType subclasses.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

/**
 * Interface EntityTypeInterface
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry
 */
interface EntityTypeInterface
{
    /**
     * Get the URL used for the "manage fields" page on this entity type.
     *
     * @param string $bundle
     *   The bundle for which to get the URL. Some entity types do not have any
     *   bundles, in which case the default of '' (empty string) can be used.
     *
     * @return string
     */
    public function getManageFieldsUrl($bundle = '');

    /**
     * Get the machine-readable entity type for this class.
     *
     * @return string
     */
    public function getEntityType();
}
