<?php
/**
 * @file
 * Represents a standard text field widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class TextWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class TextWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Text field';
    }

    // Nothing required here as the base class fill() method will do nicely.
}
