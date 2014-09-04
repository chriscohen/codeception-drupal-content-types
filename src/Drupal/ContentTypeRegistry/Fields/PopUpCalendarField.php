<?php
/**
 * @file
 * Represents a pop-up calendar date field on a Drupal entity.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Fields;

/**
 * Class PopUpCalendarField
 *
 * @todo currently this only supports entering a single "start" date. Could do with a better way here.
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Fields
 */
class PopUpCalendarField extends Field
{
    // Nothing needed here as the base class's fillField() will take care of filling the text-based date field.
}
