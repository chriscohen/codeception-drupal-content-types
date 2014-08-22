<?php
/**
 * @file
 * Yaml implementation of ContentTypeRegistryStorageInterface.
 */

namespace Codeception\Module\ContentTypes;

use Symfony\Component\Yaml\Yaml;
use Codeception\Exception\Configuration as ConfigurationException;
use Codeception\Module\ContentTypes\Fields\Field;

/**
 * Retrieve a list of content types for this site from yaml configuration.
 *
 * @package Codeception\Drupal
 */
class ContentTypeRegistryYamlStorage implements ContentTypeRegistryStorageInterface
{
    /**
     * The parsed Yaml configuration, stored to avoid having to process it multiple times from loading a file.
     *
     * @var array
     */
    protected $config = array();

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
}
