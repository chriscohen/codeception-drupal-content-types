<?php
/**
 * @file
 * Represents an organic groups reference widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class OgReferenceWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class OgReferenceWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'OG reference';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value)
    {
        // @todo do nothing here for now. Could do with something sensible/safe here.
    }
}
