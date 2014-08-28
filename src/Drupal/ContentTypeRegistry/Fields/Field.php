<?php
/**
 * @file
 * Represents a field on a Drupal entity, such as a node or taxonomy term.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

use Codeception\Exception\Configuration as ConfigurationException;

/**
 * Class Field
 */
class Field
{
    /**
     * The machine name of the field.
     *
     * @var string
     */
    protected $machine;

    /**
     * The human-readable label on the field.
     *
     * @var string
     */
    protected $label;

    /**
     * The type of the field, such as textfield, email, etc.
     *
     * Note that this should be the human-readable name as displayed on admin/structure/types/manage/NODETYPE/fields
     *
     * @var string
     */
    protected $type;

    /**
     * XPath selector used to identify this field on the node edit page.
     *
     * @var string
     */
    protected $selector;

    /**
     * The name of the widget in use on this field as displayed on admin/structure/types/manage/NODETYPE/fields
     *
     * @var string
     */
    protected $widget;

    /**
     * True if mandatory when creating/editing a node. False otherwise.
     *
     * @var bool
     */
    protected $required;

    public static $fieldsWithNoWidget = array(
        'Fieldset containing scheduling settings',
        'Meta tag module form elements.',
        'Node module element',
        'Path module form elements',
        'Poll choices',
        'Poll module settings',
        'Redirect module form elements',
        'XML sitemap module element',
    );

    /**
     * Constructor.
     *
     * @param string $machine
     * @param string $label
     * @param string $type
     * @param string $selector
     * @param string $widget
     * @param bool $required
     */
    public function __construct($machine = '', $label = '', $type = '', $selector = '', $widget = '', $required = false)
    {
        $this->machine = $machine;
        $this->label = $label;
        $this->type = $type;
        $this->selector = $selector;
        $this->widget = $widget;

        // The title field is always required regardless.
        $this->required = $label == 'Title' ? true : $required;
    }

    /**
     * Gets the field's machine name.
     *
     * @return string
     */
    public function getMachine()
    {
        return $this->machine;
    }

    /**
     * Sets the field's machine name.
     *
     * @param string $machine
     */
    public function setMachine($machine)
    {
        $this->machine = $machine;
    }

    /**
     * Gets the field label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the field label.
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Gets the field type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the field type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Gets the field selector.
     *
     * @return string
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * Sets the field selector.
     *
     * @param string $selector
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
    }

    /**
     * Gets the field's widget.
     *
     * @return string
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Sets the field's widget.
     *
     * @param string $widget
     */
    public function setWidget($widget)
    {
        $this->widget = $widget;
    }

    /**
     * Gets whether this field is required or not.
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Sets whether this field is required or not.
     *
     * @param bool $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Does this field have a widget name on the admin "manage fields" page in the "widget" column?
     *
     * @return bool
     *   True if the field has something listed in the "widget" column on the admin "manage fields" page. False if it's
     *   blank.
     */
    public function hasWidget()
    {
        return !in_array($this->getType(), static::$fieldsWithNoWidget);
    }

    /**
     * Construct a Field by parsing yaml configuration.
     *
     * @param array $yaml
     *   Yaml in a standard format as found in contentTypes.yml.
     *
     * @return Field
     *   A fully populated Field object.
     */
    public static function parseYaml($yaml)
    {
        $field = new Field();

        // If we got here, we're not dealing with a global field, so process it normally.
        if (isset($yaml['machineName'])) {
            $field->setMachine($yaml['machineName']);
        }
        if (isset($yaml['label'])) {
            $field->setLabel($yaml['label']);
        }
        if (isset($yaml['type'])) {
            $field->setType($yaml['type']);
        }
        if (isset($yaml['selector'])) {
            $field->setSelector($yaml['selector']);
        }
        if (isset($yaml['widget'])) {
            $field->setWidget($yaml['widget']);
        }
        if (isset($yaml['required']) && $yaml['required'] != 'false') {
            $field->setRequired(true);
        }

        return $field;
    }

    /**
     * Parse global fields on a content type.
     *
     * Takes a list of field names and looks them up in the global fields array, creating fields out of them, and
     * returning a collection of the desired fields.
     *
     * @param array $globals
     *   An unkeyed array of field names.
     * @param Field[] $fields
     *   Global fields as derived from ContentTypeRegistryStorageInterface->loadGlobalFields().
     */
    public static function parseGlobalFields($globals, $fields)
    {
        $output = array();

        foreach ($globals as $fieldName) {
            // Check if we're trying to reference a global field that does not exist.
            if (!isset($fields[$fieldName])) {
                throw new ConfigurationException(
                    'The ' . $fieldName .
                    ' field was set as global, but this field is not found in the list of global fields in the yaml.'
                );
            }

            $output[] = $fields[$fieldName];
        }

        return $output;
    }
}
