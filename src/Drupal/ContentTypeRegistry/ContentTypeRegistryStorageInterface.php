<?php
/**
 * @file
 * Storage interface for ContentTypeRegistry which allows content type data to be pulled from storage.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

/**
 * Interface for retrieving Drupal content types from storage.
 */
interface ContentTypeRegistryStorageInterface
{
    /**
     * @return ContentType[]
     *   Array of ContentType objects.
     */
    public function loadContentTypes();

    /**
     * @return Field[]
     *   Array of Field objects.
     */
    public function loadGlobalFields();

    /**
     * @return Field[]
     *   Array of Field objects.
     */
    public function loadGlobalExtras();

    /**
     * Populate the EntityType class with extra entity types from the
     * contentTypes.yml file. This will not return anything, but will make
     * the EntityType class aware of the extra entity types so that they can be
     * passed to the EntityType::create() method when creating entities.
     */
    public function loadEntityTypeAdditions();

    /**
     * Determine whether the config has been parsed from the data source yet.
     *
     * When the class is first created, the data source must be read. This might be a file or some other source. We do
     * this parsing once and store the result internally to avoid having to process the source multiple times. This
     * method determines whether the parsing has been done or not.
     *
     * @return bool
     *   True if the data source has been parsed (successfully). False otherwise.
     */
    public function isInitialised();

    /**
     * Perform parsing of this storage type's data source.
     *
     * @return void
     */
    public function parseDataSource();

    /**
     * Get a content type by machine name.
     *
     * @param string $type
     *   The machine name of the content type to be retrieved.
     *
     * @return ContentType|null
     *   The ContentType specified, or null if not found.
     */
    public function getContentType($type);

    /**
     * Get all content types.
     *
     * @return ContentType[]
     *   An array of all the ContentType objects.
     */
    public function getContentTypes();

    /**
     * Get a global field by machine name.
     *
     * @param string $field
     *   The machine name of the global field to be retrieved.
     *
     * @return Field|null
     *   The Field specified, or null if not found.
     */
    public function getGlobalField($field);

    /**
     * Get all global fields.
     *
     * @return Field[]
     *   An array of all the Field objects.
     */
    public function getGlobalFields();

    /**
     * Get a global extra by machine name.
     *
     * @param string $extra
     *   The machine name of the global extra to be retrieved.
     *
     * @return Field|null
     *   The extra that was specified, or null if not found.
     */
    public function getGlobalExtra($extra);

    /**
     * Get all global extras.
     *
     * @return Field[]
     *   An array of all the extras, as Field objects.
     */
    public function getGlobalExtras();
}
