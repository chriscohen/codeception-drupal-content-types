<?php
/**
 * @file
 * Represents a select list field on a Drupal entity.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

use Codeception\Util\WebInterface;

/**
 * Class SelectListField
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Fields
 */
class SelectListField extends Field
{
    /**
     * {@inheritdoc}
     */
    public function fillField(WebInterface $I, $value = null)
    {
        $I->selectOption($this->getSelector(), $value);
    }
}
