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
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/flags/manage/' . $this->getEntityType() . '/fields';
    }
}
