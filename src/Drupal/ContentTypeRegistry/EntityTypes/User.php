<?php
/**
 * @file
 * Represents the user entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

class User extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/config/people/accounts/fields';
    }
}
