<?php
/**
 * @file
 * Represents a "set of radio buttons" widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class RadioButtonsWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class RadioButtonsWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Check boxes/radio buttons';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value = null)
    {
        $I->selectOption('//div[contains(@class, "form-item-' . $this->getSelector() . '")]/input', $value);
    }
}
