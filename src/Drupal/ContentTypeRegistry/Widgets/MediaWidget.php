<?php
/**
 * @file
 * Represents a media module upload widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class MediaWidget
 *
 * @todo this will be complicated. Bypassing this one for now!
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class MediaWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Media file selector';
    }
}
