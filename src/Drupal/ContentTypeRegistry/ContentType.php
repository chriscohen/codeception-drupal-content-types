<?php
/**
 * @file
 * Base class for content types.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry;

use Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes\EntityType;
use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;
use Codeception\Util\WebInterface;

/**
 * Class ContentType
 *
 * @package Codeception\Drupal
 */
class ContentType
{
    /**
     * The machine-readable entity type of this content type.
     *
     * @var EntityType
     */
    protected $entityType;

    /**
     * The human-readable name of the content type.
     *
     * @var string
     */
    protected $humanName;

    /**
     * The machine-readable name of the content type.
     *
     * @var string
     */
    protected $machineName;

    /**
     * An array of Field objects representing the fields on this content type.
     *
     * The array is keyed by field machine name.
     *
     * @var Field[]
     */
    protected $fields = array();

    /**
     * An array of extra things the content type should know about.
     *
     * Extras are like fields, only they're not fully fledged fields. Instead they're form elements found elsewhere
     * on the create node form, such as the publication state or the "stickiness" of a content type.
     *
     * @var Field[]
     */
    protected $extras = array();

    /**
     * The selector used on this content type's node add or edit form in order to submit the page.
     *
     * A safe default is provided.
     *
     * @var string
     */
    protected $submitSelector = '#edit-submit';

    /**
     * Get the entity type.
     *
     * @return EntityType
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set the entity type.
     *
     * This will create a new EntityType subclass by using the shortname in the
     * parameter provided.
     *
     * @param string $entityType
     */
    public function setEntityType($entityType)
    {
        $this->entityType = EntityType::create($entityType);
    }

    /**
     * Get the content type human name.
     *
     * @return string
     */
    public function getHumanName()
    {
        return $this->humanName;
    }

    /**
     * Set the content type human name.
     *
     * @param string $humanName
     */
    public function setHumanName($humanName)
    {
        $this->humanName = $humanName;
    }

    /**
     * Get the content type machine name.
     *
     * @return string
     */
    public function getMachineName()
    {
        return $this->machineName;
    }

    /**
     * Set the content type machine name.
     *
     * @param string $machineName
     */
    public function setMachineName($machineName)
    {
        $this->machineName = $machineName;
    }

    /**
     * Get all of the fields for this content type.
     *
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set all of the fields for this content type.
     *
     * @param Field[] $fields
     */
    public function setAllFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Get a field with a specific machine name.
     *
     * @param string $fieldName
     * @return Field|null
     */
    public function getField($fieldName)
    {
        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : null;
    }

    /**
     * Set a field on this content type.
     *
     * @param Field $field
     */
    public function setField($field)
    {
        $this->fields[$field->getMachine()] = $field;
    }

    /**
     * Set a group of fields at the same time.
     *
     * @param Field[] $fields
     *   An array of fields. Array keys do not matter.
     */
    public function setFields($fields)
    {
        foreach ($fields as $field) {
            $this->setField($field);
        }
    }

    /**
     * Get all of the extras for this content type.
     *
     * @return Field[]
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * Set all of the extras for this content type.
     *
     * @param Field[] $extras
     */
    public function setAllExtras($extras)
    {
        $this->extras = $extras;
    }

    /**
     * Get an extra with a specific machine name.
     *
     * @param string $extraName
     * @return Field|null
     */
    public function getExtra($extraName)
    {
        return isset($this->extras[$extraName]) ? $this->extras[$extraName] : null;
    }

    /**
     * Set an extra on this content type.
     *
     * @param Field $extra
     */
    public function setExtra($extra)
    {
        $this->extras[$extra->getMachine()] = $extra;
    }

    /**
     * Set a group of extras at the same time.
     *
     * @param Field[] $extras
     *   An array of extras. Array keys do not matter.
     */
    public function setExtras($extras)
    {
        foreach ($extras as $extra) {
            $this->setExtra($extra);
        }
    }

    /**
     * Gets the submit button selector on the node add or edit form for this content type.
     *
     * @return string
     */
    public function getSubmitSelector()
    {
        return $this->submitSelector;
    }

    /**
     * Sets the submit button selector on the node add or edit form for this content type.
     *
     * Note that this is only necessary if it differs from the Drupal default value.
     *
     * @param string $submitSelector
     *   The CSS or XPath selector used to identify the submit button on the node add or edit form.
     */
    public function setSubmitSelector($submitSelector)
    {
        $this->submitSelector = $submitSelector;
    }

    /**
     * Construct a ContentType by parsing yaml configuration.
     *
     * @param array $yaml
     *   Yaml in a standard format as found in contentTypes.yml.
     * @param array $fields
     *   Yaml for the global fields in a standard format as found in contentTypes.yml.
     * @param array $extras
     *   Yaml for the global extras in a standard format as found in contentTypes.yml.
     *
     * @return ContentType
     *   A fully populated ContentType object.
     */
    public static function parseYaml($yaml, $fields = array(), $extras = array())
    {
        $contentType = new ContentType();
        $contentType->setHumanName($yaml['humanName']);
        $contentType->setMachineName($yaml['machineName']);

        // Set the entity type.
        if (isset($yaml['entityType'])) {
            $contentType->setEntityType($yaml['entityType']);
        } else {
            // Use node as our default if no entity type is set.
            $contentType->setEntityType('node');
        }

        // Set all the required fields for this content type.
        foreach ($contentType->getEntityType()->getRequiredFields() as $field) {
            $contentType->setField($field);
        }

        // Set all fields on this content type as defined in the yaml.
        if (isset($yaml['fields'])) {
            foreach ($yaml['fields'] as $key => $fieldData) {
                // The 'globals' key is the old way of writing it instead of 'globalFields' which is maintained for
                // backwards compatibility.
                if ($key == 'globals' || $key == 'globalFields') {
                    // Handle the list of global fields specially.
                    $fields = Field::parseGlobalFields($fieldData, $fields);
                    $contentType->setFields($fields);
                } else {
                    // Handle a single field definition.
                    $field = Field::parseYaml($fieldData, $fields);
                    $contentType->setField($field);
                }

            }
        }

        // Set all extras on this content type as defined in the yaml.
        if (isset($yaml['extras'])) {
            foreach ($yaml['extras'] as $key => $extraData) {
                if ($key == 'globalExtras') {
                    // Handle the list of global extras specially.
                    $extras = Field::parseGlobalFields($extraData, $extras);
                    $contentType->setExtras($extras);
                } else {
                    $extra = Field::parseYaml($extraData);
                    $contentType->setExtra($extra);
                }

            }
        }

        if (isset($yaml['submit'])) {
            $contentType->setSubmitSelector($yaml['submit']);
        }

        return $contentType;
    }

    /**
     * Fill all the fields on this content type.
     *
     * @param WebInterface $I
     *   The WebInterface (like the actor) being used within the active test scenario.
     * @param array $testData
     *   A keyed array of test data where the keys are the machine names of the fields and the values are the values
     *   for the test data.
     */
    public function fillFields(WebInterface $I, $testData = array())
    {
        // Go through each field and set its value.
        foreach ($this->getFields() as $field) {
            // If we have been given a value for this field, use it. Otherwise, use the test data provided within the
            // Field instance.
            if (isset($testData[$field->getMachine()])) {
                $field->fillField($I, $testData[$field->getMachine()]);
            } else {
                $field->fillField($I);
            }
        }
    }
}
