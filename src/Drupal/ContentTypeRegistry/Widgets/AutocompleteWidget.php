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

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        if ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            switch ($this->getField()->getType()) {
                case 'Entity Reference':
                    return '#' . $this->getSelector() . '-0-target-id';

                default:
                    return '#' . $this->getSelector();
            }
        }
    }
}
