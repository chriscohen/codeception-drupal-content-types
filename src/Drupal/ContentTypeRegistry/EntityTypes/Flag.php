<?php
/**
 * @file
 * Represents the flag entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

class Flag extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        return 'flag';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesUrl()
    {
        return 'admin/structure/flags';
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/flags/manage/' . $bundle . '/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        // There are no required fields for flags.
        return array();
    }
}
