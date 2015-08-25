<?php
/**
 * @file
 * Represents the file entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

class File extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/file-types/manage/' . $this->getEntityType() . '/fields';
    }
}
