<?php
/**
 * @file
 * Represents the file entity type.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

class File extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypesUrl()
    {
        return 'admin/structure/file-types';
    }

    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/file-types/manage/' . $bundle . '/fields';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredFields()
    {
        $fileNameField = new Field();
        $fileNameField->setLabel('File name');
        $fileNameField->setMachine('filename');
        $fileNameField->setType('File name');
        $fileNameField->setWidgetNameVisible(false);

        $filePreviewField = new Field();
        $filePreviewField->setLabel('File');
        $filePreviewField->setMachine('preview');
        $filePreviewField->setType('File preview');
        $filePreviewField->setWidgetNameVisible(false);

        return array(
            'File name' => $fileNameField,
            'File' => $filePreviewField,
        );
    }

    /**
     * {@inheritdoc}
     *
     * This entity type does not show the machine name on the types page.
     */
    public function machineNameOnTypesUrl()
    {
        return false;
    }
}
