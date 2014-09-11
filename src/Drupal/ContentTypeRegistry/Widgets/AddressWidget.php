<?php
/**
 * @file
 * Represents a full dynamic address widget on a Drupal entity form.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\Widgets;

/**
 * Class AddressWidget
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry\Widgets
 */
class AddressWidget extends Widget
{
    /**
     * Elements that make up the address. Keys are the individual field labels. Values are the end part of the selector.
     *
     * @var array
     */
    protected $elements = array();

    /**
     * Constructor.
     */
    public function __construct($yaml = array())
    {
        $this->name = 'Dynamic address form';

        // Since this is an address field, we expect to find a list of address elements in the contentTypes.yml file.
        // We import those into this widget along with the selectors used to find them.
        if (isset($yaml['elements'])) {
            $this->setElements($yaml['elements']);
        }
    }

    /**
     * Gets the address elements for this widget.
     *
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Sets the address elements for this widget.
     *
     * @param array $elements
     */
    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function getCssOrXpath()
    {
        if ($this->hasSelector()) {
            return $this->getSelector();
        } else {
            return '#' . $this->getSelector() . '-0';
        }
    }

    /**
     * {@inheritdoc}
     *
     * The $value should be an array where the keys are the field labels, such as "Address 1" or "Postcode" and the
     * values contain the data to go in those fields.
     */
    public function fill($I, $value)
    {
        $selector = $this->getCssOrXpath();

        foreach ($this->getElements() as $label => $selectorSuffix) {
            $I->fillField($selector . $selectorSuffix, $value[$label]);
        }
    }
}
