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
    public function getCssOrXpath()
    {
        return sprintf(
            '//div[contains(@class, "form-item-%s")]/input',
            $this->getSelector()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value = null)
    {
        $I->selectOption($this->getCssOrXpath(), $value);
    }

    /**
     * {@inheritdoc}
     */
    public static function selectorFromMachine($machine)
    {
        $converted = str_replace("_", "-", $machine);
        return $converted . '-und';
    }
}
