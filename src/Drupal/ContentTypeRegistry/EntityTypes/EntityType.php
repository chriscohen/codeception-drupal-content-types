<?php
/**
 * @file
 * Abstract class to create a contract for EntityType objects.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

/**
 * Abstract class EntityType
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry
 */
abstract class EntityType implements EntityTypeInterface
{
    /**
     * The machine-readable name of the entity type.
     *
     * @var string
     */
    protected $entityType;

    /**
     * Get the entity type.
     *
     * @return string
     */
    public function getEntityType() {
        return $this->entityType;
    }
}
