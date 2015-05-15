<?php
/**
 * @file
 * Represents a media module browser widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class MediaBrowserWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class MediaBrowserWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Media browser';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value)
    {
        // @todo do nothing here for now. Could do with something sensible/safe here.
    }
}
