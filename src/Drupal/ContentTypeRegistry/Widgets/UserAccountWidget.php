<?php
/**
 * @file
 * Represents the "User module account form elements." on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class UserAccountWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class UserAccountWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'User account';
    }

    // Nothing required here as the base class fill() method will do nicely.
}
