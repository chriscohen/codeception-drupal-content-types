<?php
/**
 * @file
 * Represents a full dynamic address widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class AddressWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class AddressWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Dynamic address form';
    }

    // @todo need to make a fill() method that works here.
}
