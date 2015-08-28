<?php
/**
 * @file
 * Represents the node entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

class Node extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        return 'node';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesUrl()
    {
        return 'admin/structure/types';
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/types/manage/' . $bundle . '/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        $field = new Field();
        $field->setLabel('Title');
        $field->setMachine('title');
        $field->setType('Node module element');
        $field->setWidgetNameVisible(false);

        return array('Title' => $field);
    }
}
