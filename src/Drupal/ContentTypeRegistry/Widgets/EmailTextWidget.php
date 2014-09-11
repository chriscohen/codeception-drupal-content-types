<?php
/**
 * @file
 * Represents a standard email text field widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class EmailTextWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class EmailTextWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Text field';
    }

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        if ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            return '#' . $this->getSelector() . '-0-email';
        }
    }

    // Nothing required here as the base class fill() method will do nicely.
}
