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
}
