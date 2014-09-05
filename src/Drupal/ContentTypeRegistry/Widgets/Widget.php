<?php
/**
 * @file
 * Represents a widget used on web page forms to put data into or read data from a field.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;
use Codeception\Util\WebInterface;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Class Widget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
abstract class Widget
{
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
        'Autocomplete'              => 'AutocompleteWidget',
        'Check boxes/radio buttons' => 'CheckboxesWidget',
        'Dynamic address form'      => 'AddressWidget',
        'File'                      => 'FileWidget',
        'Link'                      => 'LinkWidget',
        'Media file selector'       => 'MediaWidget',
        'Node module element'       => 'TextWidget',
        'Poll module settings'      => 'PollWidget',
        'Pop-up calendar'           => 'PopUpCalendarWidget',
        'Scheduler'                 => 'SchedulerWidget',
        'Select list'               => 'SelectListWidget',
        'Single on/off checkbox'    => 'SingleCheckboxWidget',
        'Text area (multiple rows)' => 'TextAreaWidget',
        'Text field'                => 'TextWidget',
        'Text area with a summary'  => 'WysiwygWidget',
    );

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
     * @param $field
     */
    public function setField($field)
    {
        $this->field = $field;
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
     * Create and return a widget of the specified type.
     *
     * @param string $type
     *   The type of widget. This will be a string that either describes the widget as found on the 'manage fields'
     *   admin page, or, in the case of fields that don't have widgets listed there, the type of the field itself.
     * @param Field $field
     *   The field that is to become the parent for this widget.
     *
     * @return Widget
     *   An object of a class that represents the widget that was specified.
     *
     * @throws InvalidArgumentException
     */
    public static function create($type, $field)
    {
        if (isset(static::$widgetClasses[$type])) {
            $class = 'Codeception\\Module\\Drupal\\ContentTypeRegistry\\Widgets\\' . static::$widgetClasses[$type];

            /** @var Widget $widget */
            $widget = new $class();
            $widget->setField($field);

            return $widget;
        } else {
            throw new InvalidArgumentException('Widget class could not be retrieved for the ' . $type . ' widget');
        }
    }

    /**
     * Fill this widget on a web form.
     *
     * @param WebInterface $I
     *   The WebInterface (like the actor) being used within the active test scenario.
     * @param mixed $value
     *   The value to put into the field's widget.
     */
    public function fill(WebInterface $I, $value)
    {
        $I->fillField($this->getSelector(), $value);
    }
}
