<?php
/**
 * @file
 * Represents a text area widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class TextAreaWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class TextAreaWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Text area (multiple rows)';
    }

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        return '#' . $this->getSelector() . '-0-value';
    }

    // Nothing required here as the base class fill() method will do nicely.
}
