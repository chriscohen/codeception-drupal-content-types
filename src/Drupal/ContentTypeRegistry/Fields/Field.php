<?php
/**
 * @file
 * Represents a field on a Drupal entity, such as a node or taxonomy term.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

use Codeception\Exception\Configuration as ConfigurationException;
use Codeception\Lib\Interfaces\Web;
use Codeception\Module\Drupal\ContentTypeRegistry\Widgets\Widget;

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
     * The widget in use on this field to input data on web forms.
     *
     * @var Widget
     */
    protected $widget;

    /**
     * True if mandatory when creating/editing a node. False otherwise.
     *
     * @var bool
     */
    protected $required;

    /**
     * The sequence of steps to be run before the field is filled.
     *
     * @var array
     *   Array of steps. A step is an array where the first element is the
     *   method to call and the 2nd element is an array of arguments to pass
     *   to the method.
     *
     *   e.g. array("click", array("#edit-submit"))
     *   e.g. array("fillField", array("#name", "Smith"))
     *   e.g. array("waitForJs", array("return jQuery.active == 0"))
     */
    protected $preSteps;

    /**
     * The sequence of steps to be run after the field has been filled.
     *
     * @var array
     *   Array of steps. A step is an array where the first element is the
     *   method to call and the 2nd element is an array of arguments to pass
     *   to the method.
     *
     *   e.g. array("click", array("#edit-submit"))
     *   e.g. array("fillField", array("#name", "Smith"))
     *   e.g. array("waitForJs", array("return jQuery.active == 0"))
     */
    protected $postSteps;

    /**
     * The list of roles that will not be able to see this field and should not attempt to manipulate or fill it.
     *
     * @var string[]
     */
    protected $skippedRoles;

    /**
     * Test data for this field. Can be a single value, or an array of values. If an array is used, one will be chosen
     * at random or the user can specify which one to use.
     *
     * @var mixed
     */
    protected $testData;

    /**
     * List of fields with no widget.
     *
     * If a field type is listed here, fields of this type on the 'manage
     * fields' page will be able to get away with having a blank widget.
     *
     * @var array
     */
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
     * Gets the field's widget.
     *
     * @return Widget
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Sets the field's widget.
     *
     * @param Widget $widget
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
     * Gets the sequence of steps to be run after this field is filled.
     *
     * @return array
     *   Array of steps.
     */
    public function getPostSteps()
    {
        return $this->postSteps;
    }

    /**
     * Sets the sequence of steps to be run after the field has been filled.
     *
     * @param array $post
     *   Array of steps. See $postSteps.
     */
    public function setPostSteps($post)
    {
        $this->postSteps = $post;
    }

    /**
     * Gets the sequence of steps to be run before this field is filled.
     *
     * @return array
     *   Array of steps.
     */
    public function getPreSteps()
    {
        return $this->preSteps;
    }

    /**
     * Sets the sequence of steps to be run before this field is filled.
     *
     * @param array $pre
     *   Array of steps. See $preSteps.
     */
    public function setPreSteps($pre)
    {
        $this->preSteps = $pre;
    }

    /**
     * Gets the list of roles that should skip this field.
     *
     * @return string[]
     */
    public function getSkippedRoles()
    {
        return empty($this->skippedRoles) ? array() : $this->skippedRoles;
    }

    /**
     * Sets the list of roles that should skip this field.
     *
     * @param string[] $skippedRoles
     *   The roles that should be skipped.
     */
    public function setSkippedRoles($skippedRoles)
    {
        $this->skippedRoles = $skippedRoles;
    }

    /**
     * Get the test data for this field.
     *
     * @param int|string $index
     *   If the test data is in array form (i.e. multiple bits of test data), return the specified index of the array.
     *   If this is omitted, a random element will be returned. If the test data is not in array form, then it will be
     *   returned as-is.
     * @return mixed
     *   The test data, either randomly determined, or as selected using the $index parameter.
     */
    public function getTestData($index = -1)
    {
        // If our test data is an array, we can either get a random element, or get a specific element. If it's not an
        // array, we have no choice but to return as-is.
        if (is_array($this->testData)) {
            // If an index has been requested and that index exists in the array, return that specific item. Otherwise,
            // just return a random array element.
            if ($index > -1 && isset($this->testData[$index])) {
                return $this->testData[$index];
            } else {
                return $this->testData[array_rand($this->testData)];
            }
        } else {
            return $this->testData;
        }
    }

    /**
     * Sets the test data for this field.
     *
     * @param mixed $testData
     */
    public function setTestData($testData)
    {
        // Process the value if it's a "special" value.
        $testData = static::parseSpecialValue($testData);

        $this->testData = $testData;
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
            $field->setWidget(Widget::create($yaml, $field));
        }
        if (isset($yaml['selector'])) {
            $field->getWidget()->setSelector($yaml['selector']);
        }
        if (isset($yaml['required']) && $yaml['required'] != 'false') {
            $field->setRequired(true);
        }

        $preSteps = array();
        if (isset($yaml['pre'])) {
            $preSteps[] = array('click', array($yaml['pre']));
        }
        if (isset($yaml['preSteps'])) {
            $field->setPreSteps(array_merge($preSteps, $yaml['preSteps']));
        }
        if (isset($yaml['postSteps'])) {
            $field->setPostSteps($yaml['postSteps']);
        }
        if (isset($yaml['skipRoles']) && is_array($yaml['skipRoles'])) {
            $field->setSkippedRoles($yaml['skipRoles']);
        }
        if (isset($yaml['testData'])) {
            $field->setTestData($yaml['testData']);
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
     *
     * @return array
     *   Collection of global fields.
     *
     * @throws ConfigurationException
     *   If global field does not exist.
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

    /**
     * Fill this field on a web form.
     *
     * @param Web $I
     *   The WebInterface (like the actor) being used within the active test scenario.
     * @param mixed $value
     *   The value to put into the field. If left out, will use a random value obtained from getTestData().
     */
    public function fill($I, $value = null)
    {
        // Explicitly check for null here. empty() would not cut it because it would trigger if you wanted to fill the
        // field with a 0 digit or similar.
        if (is_null($value)) {
            $value = $this->getTestData();
        }

        // Run any number of steps before filling the field.
        if (isset($this->preSteps)) {
            $this->executeSteps(
                $I,
                $this->preSteps
            );
        }

        $this->getWidget()->fill($I, $value);

        // Run any number of steps after filling the field.
        if (isset($this->postSteps)) {
            $this->executeSteps(
                $I,
                $this->postSteps
            );
        }
    }

    /**
     * Execute a series of steps.
     *
     * @param Web $I
     *   The WebInterface (like the actor) being used within the active test scenario.
     * @param array $steps
     *   An array is expected with the method to call on $I and the
     *   arguments to pass.
     */
    protected function executeSteps($I, $steps)
    {
        foreach ($steps as $step) {
            list($method, $args) = $step;
            call_user_func_array(array($I, $method), $args);
        }
    }

    /**
     * Generate a bit of random text.
     *
     * @param int $length
     *   The number of characters to include in the text.
     *
     * @return string
     *   A string of the specified length.
     */
    public static function randomText($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $output = '';
        $charLen = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $output .= substr($chars, rand(0, $charLen - 1), 1);
        }

        return $output;
    }

    /**
     * Work out if a particular field value is considered "special" (and further processing is required).
     *
     * If it's special, process it into its proper value.
     *
     * @param mixed $value
     *   The special command used to populate this field's value.
     *
     * @return mixed
     *   The processed value. For example, if random text was requested, returns random text instead of the value that
     *   requests random text.
     */
    public static function parseSpecialValue($value)
    {
        // If array, recurse values.
        if (is_array($value)) {
            $output = array();

            foreach ($value as $key => $innerValue) {
                $output[$key] = Field::parseSpecialValue($innerValue);
            }

            return $output;
        }

        // If we're not dealing with a special value we can just bail out immediately at this point.
        if (substr($value, 0, 9) != 'special::') {
            return $value;
        }

        $special = substr($value, 9);

        switch ($special) {
            case 'randomText':
            default:
                $value = static::randomText();
                break;
        }

        return $value;
    }

    /**
     * Whether to skip filling this field for a particular role.
     *
     * This is used when a role cannot see a certain field on the node form.
     *
     * @param string $role
     *   The name of the role.
     *
     * @return bool
     *   True if this field is skipped for the specified role. False if the role is not found, or it is not skipped.
     */
    public function isSkipped($role)
    {
        return in_array($role, $this->getSkippedRoles());
    }
}
