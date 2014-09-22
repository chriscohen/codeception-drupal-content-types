<?php
/**
 * @file
 * Represents a standard image upload widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class ImageWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class ImageWidget extends Widget
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'Image';
    }

    /**
     * {@inheritdoc}
     */
    public function fill($I, $value = null)
    {
        // @todo for now the attachFile() doesn't work due to a problem in PhantomJS where it returns an HTTP 405 (from
        // PhantomJS, not from the destination web server), refusing the file that is uploaded.
        //$I->attachFile($this->getCssOrXpath(), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        if ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            return '#' . $this->getSelector() . '-0-upload';
        }
    }
}
