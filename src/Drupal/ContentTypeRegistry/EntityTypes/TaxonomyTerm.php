<?php
/**
 * @file
 * Represents the taxonomy term entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

class TaxonomyTerm extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/types/taxonomy/' . $this->getEntityType() . '/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        $field = new Field();
        $field->setLabel('Name');
        $field->setMachine('name');
        $field->setType('Term name textfield');

        return array('Name' => $field);
    }
}
