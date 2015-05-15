<?php
/**
 * @file
 * Represents a widget used on web page forms to put data into or read data from a field.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;
use Codeception\Lib\Interfaces\Web;
use InvalidArgumentException;

/**
 * Class Widget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
abstract class Widget
{
    /**
     * The name of the widget, as listed on the admin 'manage fields' page.
     *
     * @var string
     */
    protected $name;

    /**
     * A reference to the field object to which this widget is attached.
     *
     * @var Field
     */
    protected $field;

    /**
     * XPath or CSS selector to select this widget on the web page.
     *
     * @var string
     */
    protected $selector;

    /**
     * Provide a map between widget names and widget classes.
     *
     * Note that some fields don't have widgets listed on the 'manage fields' admin page (such as the "Node module
     * element" for the title field). These types are passed directly here so the type is used in the array rather than
     * the widget itself.
     *
     * @var array
     */
    protected static $widgetClasses = array(
        'Autocomplete'                              => 'AutocompleteWidget',
        'Autocomplete text field'                   => 'AutocompleteWidget',
        'Check boxes'                               => 'CheckboxesWidget',
        'Dynamic address form'                      => 'AddressWidget',
        'Email::Text field'                         => 'EmailTextWidget',
        'Embedded'                                  => 'EmbeddedWidget',
        'File'                                      => 'FileWidget',
        'Geocode from another field'                => 'GeoWidget',
        'Hierarchical Select'                       => 'HierarchicalWidget',
        'Image'                                     => 'ImageWidget',
        'Link'                                      => 'LinkWidget',
        'Media file selector'                       => 'MediaWidget',
        'Node module element'                       => 'TextWidget',
        'Poll choices'                              => 'PollChoicesWidget',
        'Poll module settings'                      => 'PollSettingsWidget',
        'Pop-up calendar'                           => 'PopUpCalendarWidget',
        'Fieldset containing scheduling settings'   => 'SchedulerWidget',
        'Radio buttons'                             => 'RadioButtonsWidget',
        'Select list'                               => 'SelectListWidget',
        'Single on/off checkbox'                    => 'SingleCheckboxWidget',
        'Text area (multiple rows)'                 => 'TextAreaWidget',
        'Text field'                                => 'TextWidget',
        'Text area with a summary'                  => 'WysiwygWidget',
    );

    /**
     * Gets the name of this widget.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of this widget.
     *
     * Note that often this is not necessary as the widget's name is automatically set in the constructor.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the field to which this widget belongs.
     *
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets the field to which this widget belongs.
     *
     * @param Field $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Gets the field selector.
     *
     * If a selector has been set explicitly, use it. Otherwise, derive one from the machine name of the parent field.
     * This is useful because the vast majority of selectors can be derived from the field's machine name, so this saves
     * the need to set it in every case.
     *
     * @return string
     */
    public function getSelector()
    {
        return isset($this->selector) ? $this->selector : static::selectorFromMachine($this->getField()->getMachine());
    }

    /**
     * Gets a full CSS or XPath selector that can be applied to the web page to identify the widget.
     */
    public function getCssOrXpath()
    {
        if ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            return '#' . $this->getSelector();
        }
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
     * Create and return a widget of the specified type.
     *
     * @param string $yaml
     *   The yaml from contentTypes.yml that describes the field. Should contain the type of the widget, and,
     *   optionally, the widget name. The latter will be a string found in the 'widget' column on the 'manage fields'
     *   admin page, or, in the case of fields that don't have widgets listed there, the type of the field itself, from
     *   the 'type' column on that page.
     * @param Field $field
     *   The field that is to become the parent for this widget.
     *
     * @return Widget
     *   An object of a class that represents the widget that was specified.
     *
     * @throws InvalidArgumentException
     */
    public static function create($yaml, $field)
    {
        if (isset($yaml['type']) && $yaml['type'] == 'Email' && isset($yaml['widget'])) {
            // Handle a special case for email fields.
            // @todo might want to handle this better by making sure all type/widget combinations are considered in
            // $widgetClasses rather than only looking at the widget.
            $type = implode('::', array($yaml['type'], $yaml['widget']));
        } elseif (isset($yaml['subtype'])) {
            // Use the subtype as the type, because one was set.
            $type = $yaml['subtype'];
        } else {
            // Use the name of the widget. If there isn't one, use the type of the field instead.
            $type = isset($yaml['widget']) ? $yaml['widget'] : $yaml['type'];
        }

        if (isset(static::$widgetClasses[$type])) {
            $class = 'Codeception\\Module\\Drupal\\ContentTypeRegistry\\Widgets\\' .
                static::$widgetClasses[$type];

            /** @var Widget $widget */
            $widget = new $class($yaml);
            $widget->setField($field);

            return $widget;
        } else {
            throw new InvalidArgumentException(
                'Widget class could not be retrieved for the ' . $type . ' widget'
            );
        }
    }

    /**
     * Fill this widget on a web form.
     *
     * @param Web $I
     *   The WebInterface (like the actor) being used within the active test scenario.
     * @param mixed $value
     *   The value to put into the field's widget. Optional. If not provided, no fill will be attempted.
     */
    public function fill($I, $value = null)
    {
        if (!empty($value)) {
            $I->fillField($this->getCssOrXpath(), $value);
        }
    }

    /**
     * Derive a selector from a machine name.
     *
     * For example, if the machine name is field_foo_bar, the derived selector would be edit-field-foo-bar-und.
     *
     * @param string $machine
     *   The machine name from which to derive a selector.
     *
     * @return string
     *   The selector that has been derived from the machine name.
     */
    public static function selectorFromMachine($machine)
    {
        $converted = str_replace("_", "-", $machine);
        return 'edit-' . $converted . '-und';
    }

    /**
     * Determine whether a selector has been set manually for this widget.
     *
     * @return bool
     *   True if the selector has been set manually. False otherwise.
     */
    public function hasSelector()
    {
        return isset($this->selector);
    }
}
