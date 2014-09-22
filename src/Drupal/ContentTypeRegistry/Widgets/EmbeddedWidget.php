<?php
/**
 * @file
 * Represents an embedded widget on a Drupal entity form.
 *
 * This is used by things like field collection fields.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class EmbeddedWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class EmbeddedWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Embedded';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value = null)
    {
        // @todo for now, do nothing. We need to work out some way of embedding further fields and widgets inside this
        // embedded field.
    }
}
