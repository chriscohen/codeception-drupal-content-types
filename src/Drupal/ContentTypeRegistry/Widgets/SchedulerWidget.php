<?php
/**
 * @file
 * Represents a scheduler widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class SchedulerWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class SchedulerWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Fieldset containing scheduling settings';
    }

    // @todo need to make a fill() method that works here.
}
