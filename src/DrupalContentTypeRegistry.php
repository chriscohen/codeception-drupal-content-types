<?php
/**
 * @file
 * Abstracted registry collection for content types.
 */

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Drupal\ContentTypeRegistry\ContentType;
use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;
use Codeception\Module\Drupal\ContentTypeRegistry\ContentTypeRegistryStorageInterface;

/**
 * Class DrupalContentTypeRegistry
 *
 * @package Codeception\Drupal
 */
class DrupalContentTypeRegistry extends Module
{
    /**
     * An array of ContentType objects.
     *
     * @var ContentType[]
     */
    protected $contentTypes = array();

    /**
     * An array of field definitions that apply to multiple content types.
     *
     * This is for use when the field is exactly the same on multiple types, to avoid defining it a load of times for
     * no reason.
     *
     * @var Field[]
     */
    protected $globalFields = array();

    /**
     * Initialize the content types from the storage device specified.
     *
     * @param ContentTypeRegistryStorageInterface $storage
     *   The storage interface.
     */
    public function initialize(ContentTypeRegistryStorageInterface $storage)
    {
        $this->storage = $storage;
        $this->contentTypes = $storage->loadContentTypes();
    }

    /**
     * Get a content type by machine name.
     *
     * @param string $type
     *   The machine name of the content type to be retrieved.
     *
     * @return ContentType|null
     *   The ContentType specified, or null if not found.
     */
    public function getContentType($type)
    {
        return isset($this->contentTypes[$type]) ? $this->contentTypes[$type] : null;
    }

    /**
     * Get all content types.
     *
     * @return ContentType[]
     *   An array of all the ContentType objects.
     */
    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    /**
     * Get a global field by machine name.
     *
     * @param string $field
     *   The machine name of the global field to be retrieved.
     *
     * @return Field|null
     *   The Field specified, or null if not found.
     */
    public function getGlobalField($field)
    {
        return isset($this->globalFields[$field]) ? $this->globalFields[$field] : null;
    }

    /**
     * Get all global fields.
     *
     * @return Field[]
     *   An array of all the Field objects.
     */
    public function getGlobalFields()
    {
        return $this->globalFields;
    }
}
