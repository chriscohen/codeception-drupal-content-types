<?php
/**
 * @file
 * Represents an autocomplete widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class AutocompleteWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class AutocompleteWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Autocomplete';
    }

    // Nothing required here as the base class fill() method will do nicely.
}
