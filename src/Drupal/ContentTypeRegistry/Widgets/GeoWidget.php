<?php
/**
 * @file
 * Represents a geofield widget.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class GeoWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class GeoWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Geocode from another field';
    }

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        if ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            return '.' . $this->getSelector();
        }
    }

    /**
     * Gets the selector for this field.
     *
     * This is overridden because of the way geofield shows map data.
     *
     * @return string
     */
    public function getSelector()
    {
        return 'field-name-' . str_replace("_", "-", $this->getField()->getMachine());
    }

    // There is no fill() method because this field's value is derived from another field and does not have a widget.
}
