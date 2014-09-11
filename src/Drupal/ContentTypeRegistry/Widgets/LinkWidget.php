<?php
/**
 * @file
 * Represents a link widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class LinkWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class LinkWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Link';
    }

    /**
     * {@inheritdoc}
     *
     * For this field, $value should be an array with the 'title' and 'url' keys.
     */
    public function fill($I, $value = null)
    {
        $I->fillField($this->getCssOrXpath() . '-0-title', $value['title']);
        $I->fillField($this->getCssOrXpath() . '-0-url', $value['url']);
    }
}
