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
use Codeception\Module\Drupal\ContentTypeRegistry\ContentTypeRegistryYamlStorage;
use Codeception\Util\WebInterface;
use Codeception\Module\Drupal\Pages\AdminNodeAddPage;
use InvalidArgumentException;

/**
 * Class DrupalContentTypeRegistry
 *
 * @package Codeception\Module
 */
class DrupalContentTypeRegistry extends Module
{
    /**
     * The storage class used by this content type registry.
     *
     * @var ContentTypeRegistryStorageInterface
     */
    protected $storage = null;

    /**
     * Hook that runs before each suite. Initialize content types here.
     */
    public function _beforeSuite($settings = array())
    {
        $this->storage = new ContentTypeRegistryYamlStorage();
    }

    /**
     * Initialize the content types from the storage device specified.
     *
     * @param ContentTypeRegistryStorageInterface $storage
     *   The storage interface.
     */
    public function initializeContentTypes(ContentTypeRegistryStorageInterface $storage)
    {
        $this->$storage = $storage;
    }

    /**
     * Determine whether the content type registry has been initialized.
     *
     * @return bool
     */
    public function isContentTypeRegistryInitialized()
    {
        return !is_null($this->storage);
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
        return $this->storage->getContentType($type);
    }

    /**
     * Get all content types.
     *
     * @return ContentType[]
     *   An array of all the ContentType objects.
     */
    public function getContentTypes()
    {
        return $this->storage->getContentTypes();
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
        return $this->storage->getGlobalField($field);
    }

    /**
     * Get all global fields.
     *
     * @return Field[]
     *   An array of all the Field objects.
     */
    public function getGlobalFields()
    {
        return $this->storage->getGlobalFields();
    }

    /**
     * Determines if a content type is a valid type.
     *
     * @param string $type
     *   The machine-readable content type to check.
     *
     * @return bool
     *   TRUE if the content type is valid. FALSE otherwise.
     */
    public function isValidContentType($type)
    {
        // Deal with a situation where Codeception passes in a test object.
        if (is_object($type)) {
            return true;
        }

        return !is_null($this->storage->getContentType($type));
    }

    /**
     * Create a node of the specified type.
     *
     * Note that this will not log the user in, so you need to make sure you are already logged in with a user with
     * sufficient privileges to access the desired node creation page.
     *
     * @param WebInterface $I
     *   A reference to the Tester object being used.
     * @param string $type
     *   The machine name of the content type to be created.
     * @param array $data
     *   If you want to provide custom data, the keys in this array should be the machine names of the fields to be
     *   filled, and the values should be the data to be used. Any fields ommitted here will use any testData from
     *   contentTypes.yml to obtain their values.
     */
    public function createNode(WebInterface $I, $type, $data)
    {
        // Make sure we are trying to create a valid content type.
        if (!$this->isValidContentType($type)) {
            throw new InvalidArgumentException('"' . $type . '" is not a valid content type');
        }

        $I->amOnPage(AdminNodeAddPage::route($type));

        $contentType = $this->getContentType($type);

        foreach ($contentType->getFields() as $field) {
            if (isset($data[$field->getMachine()])) {
                $field->fill($I, $data[$field->getMachine()]);
            } else {
                $field->fill($I);
            }
        }

        $I->click($contentType->getSubmitSelector());
    }
}
