<?php
/**
 * @file
 * Represents a video embed field widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class VideoWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class VideoWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Video';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value)
    {
        // @todo do nothing here for now. Could do with something sensible/safe here.
    }
}
