<?php
/**
 * @file
 * Represents a language select list widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class LanguageWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class LanguageWidget extends SelectListWidget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Language';
    }
}
