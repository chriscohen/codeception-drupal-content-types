<?php
/**
 * @file
 * Represents a select list widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class SelectListWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class SelectListWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Select list';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value = null)
    {
        if (!empty($value)) {
            $I->selectOption($this->getCssOrXpath(), $value);
        }
    }
}
