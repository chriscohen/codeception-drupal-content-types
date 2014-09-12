<?php
/**
 * @file
 * Represents a "set of checkboxes" widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class CheckboxesWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class CheckboxesWidget extends Widget
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
     *
     * @param string $option
     *   The label on the checkbox that is to be checked or unchecked.
     */
    public function getCssOrXpath($option)
    {
        return sprintf(
            '//div[@id="%s"]//label[contains(text(), "%s")]/../input',
            $this->getSelector(),
            $option
        );
    }

    /**
     * {@inheritdoc}
     *
     * The $value parameter should contain an array of all checkboxes to be checked or unchecked (have their status
     * altered). The key should be the label on the checkbox. For example, if the selector is #edit-checkboxes-und
     * (which describes the checkboxes container) and the exact checkbox you want to check is labelled "Foobarbaz",
     * then the key should just be 'Foobarbaz'. The value should be true if you want to check the box and false if you
     * want to uncheck it. Any checkboxes not found in the $value array will be left alone.
     */
    public function fill($I, $value = null)
    {
        // Skip this if there is no value set.
        if (!empty($value)) {
            foreach ($value as $option => $state) {
                if ($state === true) {
                    $I->checkOption($this->getCssOrXpath($option));
                } else {
                    $I->uncheckOption($this->getCssOrXpath($option));
                }
            }
        }
    }
}
