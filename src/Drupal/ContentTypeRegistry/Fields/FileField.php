<?php
/**
 * @file
 * Represents a standard file upload field on a Drupal entity.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

use Codeception\Util\WebInterface;

/**
 * Class FileField
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Fields
 */
class FileField extends Field
{
    /**
     * {@inheritdoc}
     */
    public function fillField(WebInterface $I, $value = null)
    {
        $I->attachFile($this->getSelector(), $value);
    }
}
