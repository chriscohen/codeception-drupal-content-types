<?php
/**
 * @file
 * Represents the asset entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

class Asset extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        return 'asset';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesUrl()
    {
        return 'admin/structure/assets';
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/assets/manage/' . $bundle . '/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        $field = new Field();
        $field->setLabel('Title');
        $field->setMachine('title');
        $field->setType('Asset module element');
        $field->setWidgetNameVisible(false);

        return array('Title' => $field);
    }
}
