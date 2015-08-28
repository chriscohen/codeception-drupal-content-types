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
    public function getEntityType()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     *
     * Do not return anything because there is no 'user types' page in Drupal.
     */
    public function getTypesUrl()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/config/people/accounts/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        return array();
    }
}
