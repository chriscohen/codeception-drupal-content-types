<?php
/**
 * @file
 * Represents a "set of checkboxes" field on a Drupal entity.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

use Codeception\Util\WebInterface;

/**
 * Class CheckboxesField
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Fields
 */
class CheckboxesField extends Field
{
    /**
     * {@inheritdoc}
     *
     * The $value parameter should contain an array of all checkboxes to be checked or unchecked (have their status
     * altered). The key should be the bit after the selector. For example, if the selector is #edit-checkboxes-und
     * (which describes the checkboxes container) and the exact checkbox you want to check is #edit-checkboxes-und-69,
     * then the key should just be '69'. The value should be true if you want to check the box and false if you want to
     * uncheck it. Any checkboxes not found in the $value array will be left alone.
     *
     * @todo this will currently ONLY work with checkboxes and not radio buttons, but there is only one widget that
     * covers both checkboxes and radio buttons.
     */
    public function fillField(WebInterface $I, $value = null)
    {
        foreach ($value as $selector => $state) {
            $fullSelector = $this->getSelector() . '-' . $selector;

            if ($state === true) {
                $I->checkOption($fullSelector);
            } else {
                $I->uncheckOption($fullSelector);
            }
        }
    }
}
