<?php
/**
 * @file
 * Represents a media module upload widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class MediaWidget
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

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value)
    {
        // @todo do nothing here for now. Could do with something sensible/safe here.
    }
}
