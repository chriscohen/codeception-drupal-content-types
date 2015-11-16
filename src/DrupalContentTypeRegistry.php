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
use Codeception\Lib\Interfaces\Web as WebInterface;
use Codeception\Module\Drupal\Pages\AdminNodeAddPage;
use Codeception\Module\Drupal\Pages\Page;
use Codeception\Module\Drupal\Pages\NodePage;
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
     *   A reference to the Actor object being used.
     * @param string $type
     *   The machine name of the content type to be created.
     * @param array $data
     *   If you want to provide custom data, the keys in this array should be the machine names of the fields to be
     *   filled, and the values should be the data to be used. Any fields ommitted here will use any testData from
     *   contentTypes.yml to obtain their values.
     * @param string|null $role
     *   The role being used to fill out the node edit form. This is used to determine which fields need to be skipped
     *   for the current role (as not all fields will appear for all roles). If not provided, no role will be assumed
     *   and all fields will be used.
     *
     * @return int
     *   The node ID of the node that has just been created.
     */
    public function createNode($I, $type, $data = array(), $role = null)
    {
        // Make sure we are trying to create a valid content type.
        if (!$this->isValidContentType($type)) {
            throw new InvalidArgumentException('"' . $type . '" is not a valid content type');
        }

        $I->amOnPage(AdminNodeAddPage::route($type));

        $contentType = $this->getContentType($type);
        $title = '';

        foreach ($contentType->getFields() as $field) {
            // Skip this field if we are using a role that doesn't see it.
            if ($field->isSkipped($role)) {
                continue;
            }

            // Save the title to check later on that the node was created properly.
            if ($field->getMachine() == 'title') {
                // If we've passed in a custom title use that, otherwise use the default field test data.
                if (isset($data['title'])) {
                    $title = $data['title'];
                } else {
                    $title = $field->getTestData();
                }
            }

            if (isset($data[$field->getMachine()])) {
                $field->fill($I, $data[$field->getMachine()]);
            } else {
                $field->fill($I);
            }
        }

        // Handle any 'extras' on the node creation form that aren't fields but still need user interaction.
        foreach ($contentType->getExtras() as $extra) {
            // Skip this extra if we are using a role that doesn't see it.
            if ($extra->isSkipped($role)) {
                continue;
            }

            if (isset($data[$extra->getMachine()])) {
                $extra->fill($I, $data[$extra->getMachine()]);
            } else {
                $extra->fill($I);
            }
        }

        // Submit the node.
        $I->click($contentType->getSubmitSelector());

        // Check that the node was created properly.
        $msg = sprintf(
            '%s %s has been created.',
            $contentType->getHumanName(),
            $title
        );

        $nid = $I->grabLastCreatedNid($I);

        $I->seeCreateNodeWasSuccessful($I, $msg, $nid);

        return $nid;
    }

    /**
     * Delete a node with the specified node ID.
     *
     * Note that this will not log the user in, so you should ensure the user is logged in and has sufficient
     * privileges to complete the node deletion.
     *
     * @param WebInterface $I
     *   A reference to the Actor being used.
     * @param int $nid
     *   The node ID of the node to be deleted.
     */
    public function deleteNode($I, $nid)
    {
        if (isset($nid)) {
            $I->amOnPage(NodePage::route($nid, true));

            $I->click('#edit-delete');
            $I->see('Are you sure you want to delete');

            // We're now on the confirm deletion page so click that confirm button too.
            $I->click('#edit-submit');
            $I->seeDeleteNodeWasSuccessful($I, $nid);
        }
    }

    /**
     * Gets the node ID of the last created node.
     *
     * This should only be called just after a node has been saved and we're on the node view page (such as node/1337).
     *
     * @param WebInterface $I
     *   A reference to the Actor object being used.
     *
     * @return mixed
     *   $nid from from node edit tab, or null if not found.
     */
    public function grabLastCreatedNid($I)
    {
        // Grab the node id from the Edit tab once the node has been saved.
        $edit_url = $I->grabAttributeFrom(Page::$nodeEditTabLinkSelector, 'href');
        $matches = array();

        if (preg_match('~/node/(\d+)/edit~', $edit_url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check a node creation was successful.
     *
     * This can be overridden in other modules (e.g. the Helper) if
     * node creation success needs to be checked in other ways.
     *
     * @param WebInterface $I
     *   A reference to the Actor being used.
     * @param string $msg
     *   The success message that should be displayed by Drupal.
     * @param int $nid
     *   The created nid.
     */
    public function seeCreateNodeWasSuccessful($I, $msg, $nid)
    {
        $I->see($msg, ".alert-success");
        $I->dontSee(" ", ".messages.error");
    }

    /**
     * Check a node deletion was successful.
     *
     * This can be overridden in other modules (e.g. the Helper) if
     * node deletion success needs to be checked in other ways.
     *
     * @param WebInterface $I
     *   A reference to the Actor being used.
     * @param int $nid
     *   The deleted nid.
     */
    public function seeDeleteNodeWasSuccessful($I, $nid)
    {
        $I->see("has been deleted.", ".alert-success");
    }
}
