<?php
/**
 * @file
 * Yaml implementation of ContentTypeRegistryStorageInterface.
 */

namespace Codeception\Module\Drupal\ContentTypeRegistry;

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
     * An array of extra definitions that apply to multiple content types.
     *
     * This is for use when the extra is exactly the same on multiple types, to avoid defining it a load of times for
     * no reason.
     *
     * @var Field[]
     */
    protected static $globalExtras = array();

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
        if (empty(static::$globalFields)) {
            static::$globalFields = $this->loadGlobalFields();
        }
        if (empty(static::$globalExtras)) {
            static::$globalExtras = $this->loadGlobalExtras();
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
            $this->config = Yaml::parse($suiteConfigFile);
        } elseif (file_exists($globalConfigFile)) {
            $this->config = Yaml::parse($globalConfigFile);
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
     */
    public function loadGlobalExtras()
    {
        // Make sure to initialise by reading the data source.
        if (!$this->isInitialised()) {
            $this->parseDataSource();
        }

        $globalExtras = array();

        if (empty($this->config)) {
            throw new ConfigurationException("Configuration file is invalid");
        }

        if (isset($this->config['GlobalExtras'])) {
            foreach ($this->config['GlobalExtras'] as $extraData) {
                $extra = Field::parseYaml($extraData);
                $globalExtras[$extra->getMachine()] = $extra;
            }
        }

        return $globalExtras;
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
        $globalExtras = $this->loadGlobalExtras();
        $contentTypes = array();

        if (empty($this->config)) {
            throw new ConfigurationException("Configuration file is invalid");
        }

        if (isset($this->config['ContentTypes'])) {
            foreach ($this->config['ContentTypes'] as $contentTypeData) {
                $contentType = ContentType::parseYaml($contentTypeData, $globalFields, $globalExtras);
                $contentTypes[$contentType->getMachineName()] = $contentType;
            }
        } else {
            throw new ConfigurationException("No Drupal content types are configured");
        }

        return $contentTypes;
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

    /**
     * {@inheritdoc}
     */
    public function getGlobalExtra($extra)
    {
        return isset(static::$globalExtras[$extra]) ? static::$globalExtras[$extra] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobalExtras()
    {
        return static::$globalExtras;
    }
}
