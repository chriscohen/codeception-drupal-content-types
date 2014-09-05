<?php
/**
 * @file
 * Represents a link widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

use Codeception\Util\WebInterface;

/**
 * Class LinkWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class LinkWidget extends Widget
{
    /**
     * {@inheritdoc}
     *
     * For this field, $value should be an array with the 'title' and 'url' keys.
     */
    public function fill(WebInterface $I, $value = null)
    {
        $I->fillField($this->getSelector() . '-und-0-title', $value['title']);
        $I->fillField($this->getSelector() . '-und-0-url', $value['url']);
    }
}
