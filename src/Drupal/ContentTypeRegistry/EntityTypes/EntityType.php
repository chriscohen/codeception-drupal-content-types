<?php
/**
 * @file
 * Abstract class to create a contract for EntityType objects.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

use InvalidArgumentException;

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
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * A list of class names for the various entity types that are "standard" on
     * a Drupal site. This can be further extended by suites implementing this
     * functionality.
     *
     * @var array
     */
    public static $entityTypes = array(
        'node' => 'Node',
        'taxonomyTerm' => 'TaxonomyTerm',
        'file' => 'File',
        'flag' => 'Flag',
        'user' => 'User',
    );

    /**
     * Extra EntityType objects provided by the suite's contentTypes.yml.
     *
     * An array where the keys are the short names and the values are the full
     * class paths to the EntityType subclasses.
     *
     * @var array
     */
    public static $entityTypeAdditions = array();

    /**
     * Generate a new EntityType subclass based on the provided shortname.
     *
     * @param string $shortName
     *   The short name of the entity type such as "node" or "taxonomyTerm".
     *
     * @return EntityType
     *   A fresh EntityType of the class specified by the shortname argument.
     */
    public static function create($shortName)
    {
        // Check if we have this entity type registered in the base set.
        if (isset(static::$entityTypes[$shortName])) {
            $className = 'Codeception\\Module\\Drupal\\ContentTypeRegistry\\EntityTypes\\' . $shortName;
        } elseif (isset(static::$entityTypeAdditions[$shortName])) {
            $className = static::$entityTypeAdditions[$shortName];
        } else {
            throw new InvalidArgumentException(
                'EntityType class could not be retrieved for the ' . $shortName . ' entity type'
            );
        }

        /** @var EntityType $className */
        $output = new $className();
        return $output;
    }
}
