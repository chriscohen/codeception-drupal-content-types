<?php
/**
 * @file
 * Represents a standard file upload widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class FileWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class FileWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'File';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value = null)
    {
        $I->attachFile($this->getSelector(), $value);
    }
}
