<?php
/**
 * @file
 * Represents a standard file upload widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

use Codeception\Util\WebInterface;

/**
 * Class FileWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class FileWidget extends Widget
{
    /**
     * {@inheritdoc}
     */
    public function fill(WebInterface $I, $value = null)
    {
        $I->attachFile($this->getSelector(), $value);
    }
}
