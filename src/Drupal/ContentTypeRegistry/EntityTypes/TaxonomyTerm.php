<?php
/**
 * @file
 * Represents the taxonomy term entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

class TaxonomyTerm extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/types/taxonomy/' . $this->getEntityType() . '/fields';
    }
}
