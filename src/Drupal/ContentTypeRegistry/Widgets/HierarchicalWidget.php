<?php
/**
 * @file
 * Represents a hierarchical select widget.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class AutocompleteWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class HierarchicalWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Hierarchical Select';
    }

    /**
     * {@inheritdoc}
     *
     * We can't do anything here just yet with hierarchical stuff, so we will
     * actually just do nothing.
     */
    public function fill()
    {

    }
}
