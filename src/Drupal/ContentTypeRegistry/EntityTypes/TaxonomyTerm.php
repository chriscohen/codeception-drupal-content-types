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
    public function getEntityType()
    {
        return 'taxonomyTerm';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesUrl()
    {
        return 'admin/structure/taxonomy';
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/taxonomy/' . $bundle . '/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        $fieldName = new Field();
        $fieldName->setLabel('Name');
        $fieldName->setMachine('name');
        $fieldName->setType('Term name textfield');
        $fieldName->setWidgetNameVisible(false);

        $fieldDescription = new Field();
        $fieldDescription->setLabel('Description');
        $fieldDescription->setMachine('description');
        $fieldDescription->setType('Term description textarea');
        $fieldDescription->setWidgetNameVisible(false);

        return array(
            'Name' => $fieldName,
            'Description' => $fieldDescription,
        );
    }

    /**
     * {@inheritdoc}
     *
     * This entity type does not show machine name on the types page.
     */
    public function machineNameOnTypesUrl()
    {
        return false;
    }
}
