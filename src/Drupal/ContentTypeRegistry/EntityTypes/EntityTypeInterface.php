<?php
/**
 * @file
 * Creates contract for methods implemented in EntityType subclasses.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes;

use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

/**
 * Interface EntityTypeInterface
 *
 * @package Codeception\Module\Drupal\ContentTypeRegistry
 */
interface EntityTypeInterface
{
    /**
     * Get the URL used for the list of bundles on this entity type.
     *
     * For example, on the node entity type, this would be admin/structure/types
     *
     * @return string|null
     *   Usually returns the URL for the bundle list page for this entity type,
     *   but if there isn't one (such as the case with the user entity type),
     *   it can return null.
     */
    public function getTypesUrl();

    /**
     * Get the URL used for the "manage fields" page on this entity type.
     *
     * @param string $bundle
     *   The bundle for which to get the URL. Some entity types do not have any
     *   bundles, in which case the default of '' (empty string) can be used.
     *
     * @return string
     */
    public function getManageFieldsUrl($bundle = '');

    /**
     * Get the machine-readable entity type for this class.
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Get collection of fields that are required on this entity type.
     *
     * @return Field[]
     *   An array of Field objects representing fields that are required on this
     *   entity type. This might be an empty array because some entity types do
     *   not have required fields.
     */
    public function getRequiredFields();

    /**
     * Whether the machine name of this type is displayed on the types page.
     *
     * For example, when you go to the node types page, the machine name of each
     * node type is shown, but when you go to the file types page, it is not, so
     * this function allows tests to determine if they are expecting to see the
     * machine name on the types page or not.
     *
     * @return bool
     */
    public function machineNameOnTypesUrl();
}
