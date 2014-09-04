<?php
/**
 * @file
 * Represents a link field on a Drupal entity.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

use Codeception\Util\WebInterface;

/**
 * Class LinkField
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Fields
 */
class LinkField extends Field
{
    /**
     * {@inheritdoc}
     *
     * For this field, $value should be an array with the 'title' and 'url' keys.
     */
    public function fillField(WebInterface $I, $value = null)
    {
        $I->fillField($this->getSelector() . '-und-0-title', $value['title']);
        $I->fillField($this->getSelector() . '-und-0-url', $value['url']);
    }
}
