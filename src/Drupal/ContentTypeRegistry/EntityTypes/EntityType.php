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
        'asset'         => 'Asset',
        'file'          => 'File',
        'flag'          => 'Flag',
        'node'          => 'Node',
        'taxonomyTerm'  => 'TaxonomyTerm',
        'user'          => 'User',
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
     * {@inheritdoc}
     *
     * No implementation here but subclasses must implement this themselves.
     */
    public function getTypesUrl()
    {
        return '';
    }

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
        $className = static::getClassName($shortName);

        /** @var EntityType $className */
        $output = new $className();
        return $output;
    }

    /**
     * Given an entity type shortName, get the full namespaced class.
     *
     * @param string $shortName
     *   The shortName for the entity type such as node or taxonomyTerm.
     *
     * @return string
     *   The full namespaced class for this EntityType subclass.
     */
    protected static function getClassName($shortName)
    {
        if (isset(static::$entityTypes[$shortName])) {
            return 'Codeception\\Module\\Drupal\\ContentTypeRegistry\\EntityTypes\\' . static::$entityTypes[$shortName];
        } elseif (isset(static::$entityTypeAdditions[$shortName])) {
            return static::$entityTypeAdditions[$shortName];
        } else {
            throw new InvalidArgumentException(
                'EntityType class could not be retrieved for the ' . $shortName . ' entity type'
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * Default implementation is that the machine name IS on the types page.
     * EntityType subclasses only need to override this if their types page does
     * not have a machine name on it.
     */
    public function machineNameOnTypesUrl()
    {
        return true;
    }
}
