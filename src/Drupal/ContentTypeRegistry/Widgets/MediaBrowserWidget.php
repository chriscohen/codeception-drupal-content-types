<?php
/**
 * @file
 * Represents a media module upload widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class MediaBrowserWidget
 *
 * Same as MediaWidget, but has a different label.
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class MediaBrowserWidget extends MediaWidget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Media browser';
    }
}
