<?php
/**
 * @file
 * Represents the node entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

class Node extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/types/manage/' . $this->getEntityType() . '/fields';
    }
}
