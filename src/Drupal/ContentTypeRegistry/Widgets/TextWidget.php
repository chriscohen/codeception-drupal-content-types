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

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        if ($this->getField()->getMachine() == 'title') {
            return '#edit-title';
        } elseif ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            return '#' . $this->getSelector() . '-0-value';
        }
    }

    // Nothing required here as the base class fill() method will do nicely.
}
