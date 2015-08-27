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
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/file-types/manage/' . $this->getEntityType() . '/fields';
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

        $filePreviewField = new Field();
        $filePreviewField->setLabel('File');
        $filePreviewField->setMachine('preview');
        $filePreviewField->setType('File preview');

        return array(
            'File name' => $fileNameField,
            'File' => $filePreviewField,
        );
    }
}
