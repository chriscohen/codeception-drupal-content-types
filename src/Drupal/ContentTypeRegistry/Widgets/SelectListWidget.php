<?php
/**
 * @file
 * Represents a select list widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

use Codeception\Util\WebInterface;

/**
 * Class SelectListWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class SelectListField extends Widget
{
    /**
     * {@inheritdoc}
     */
    public function fill(WebInterface $I, $value = null)
    {
        $I->selectOption($this->getSelector(), $value);
    }
}
