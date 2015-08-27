<?php
/**
 * @file
 * Yaml implementation of ContentTypeRegistryStorageInterface.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry;

use Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes\EntityType;
use Symfony\Component\Yaml\Yaml;
use Codeception\Exception\Configuration as ConfigurationException;
use Codeception\Module\Drupal\ContentTypeRegistry\Fields\Field;

/**
 * Retrieve a list of content types for this site from yaml configuration.
 *
 * @package Codeception\Drupal
 */
class ContentTypeRegistryYamlStorage implements ContentTypeRegistryStorageInterface
{
    /**
     * An array of ContentType objects.
     *
     * @var ContentType[]
     */
    protected static $contentTypes = array();

    /**
     * An array of field definitions that apply to multiple content types.
     *
     * This is for use when the field is exactly the same on multiple types, to avoid defining it a load of times for
     * no reason.
     *
     * @var Field[]
     */
    protected static $globalFields = array();

    /**
     * The parsed Yaml configuration, stored to avoid having to process it multiple times from loading a file.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Constructor.
     *
     * Here we initialize the internal static storage from the yaml.
     */
    public function __construct()
    {
        if (empty(EntityType::$entityTypeAdditions)) {
            $this->loadEntityTypeAdditions();
        }
        if (empty(static::$globalFields)) {
            static::$globalFields = $this->loadGlobalFields();
        }
        if (empty(static::$contentTypes)) {
            static::$contentTypes = $this->loadContentTypes();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isInitialised()
    {
        return !empty($this->config);
    }

    /**
     * {@inheritdoc}
     */
    public function parseDataSource()
    {
        $suite = SuiteSettings::$suiteName;

        // Get content types from configuration.
        //
        // If there is a content types yaml file in the current suite, use it. Otherwise, look for a global content
        // types yaml file instead.
        //
        // @todo: could potentially pass in a var to load a different filename.
        $suiteConfigFile = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . $suite .
            DIRECTORY_SEPARATOR . 'contentTypes.yml';
        $globalConfigFile = getcwd() . DIRECTORY_SEPARATOR . 'tests/contentTypes.yml';

        if (file_exists($suiteConfigFile)) {
            $yaml = file_get_contents($suiteConfigFile);
            $this->config = Yaml::parse($yaml);
        } elseif (file_exists($globalConfigFile)) {
            $yaml = file_get_contents($globalConfigFile);
            $this->config = Yaml::parse($yaml);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadGlobalFields()
    {
        // Make sure to initialise by reading the data source.
        if (!$this->isInitialised()) {
            $this->parseDataSource();
        }

        $globalFields = array();

        if (empty($this->config)) {
            throw new ConfigurationException("Configuration file is invalid");
        }

        if (isset($this->config['GlobalFields'])) {
            foreach ($this->config['GlobalFields'] as $fieldData) {
                $field = Field::parseYaml($fieldData);
                $globalFields[$field->getMachine()] = $field;
            }
        }

        return $globalFields;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConfigurationException
     */
    public function loadContentTypes()
    {
        // Make sure to initialise by reading the data source.
        if (!$this->isInitialised()) {
            $this->parseDataSource();
        }

        $globalFields = $this->loadGlobalFields();
        $contentTypes = array();

        if (empty($this->config)) {
            throw new ConfigurationException("Configuration file is invalid");
        }

        if (isset($this->config['ContentTypes'])) {
            foreach ($this->config['ContentTypes'] as $contentTypeData) {
                $contentType = ContentType::parseYaml($contentTypeData, $globalFields);
                $contentTypes[$contentType->getMachineName()] = $contentType;
            }
        } else {
            throw new ConfigurationException("No Drupal content types are configured");
        }

        return $contentTypes;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConfigurationException
     */
    public function loadEntityTypeAdditions()
    {
        // Make sure to initialise by reading the data source.
        if (!$this->isInitialised()) {
            $this->parseDataSource();
        }

        if (empty($this->config)) {
            throw new ConfigurationException("Configuration file is invalid");
        }

        // Try to find further EntityTypes in the contentTypes.yml and register
        // them as further entity types.
        if (isset($this->config['EntityTypes'])) {
            foreach ($this->config['EntityTypes'] as $entityTypeKey => $entityTypeAddition) {
                EntityType::$entityTypeAdditions[$entityTypeKey] = $entityTypeAddition;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType($type)
    {
        return isset(static::$contentTypes[$type]) ? static::$contentTypes[$type] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypes()
    {
        return static::$contentTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobalField($field)
    {
        return isset(static::$globalFields[$field]) ? static::$globalFields[$field] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobalFields()
    {
        return static::$globalFields;
    }
}
