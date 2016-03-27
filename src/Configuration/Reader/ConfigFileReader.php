<?php
namespace ZohoBooksAL\Configuration\Reader;

class ConfigFileReader implements ReaderInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * ConfigPopulate constructor.
     *
     * @param string $configurationFileName Path to the configuration file
     */
    public function __construct($configurationFileName)
    {
        if (!file_exists($configurationFileName)) {
            throw new InvalidConfigException('Could not found configuration file ' . $configurationFileName);
        }

        $config = include $configurationFileName;

        if (empty($config) || !is_array($config)) {
            throw new InvalidConfigException('Config is empty!');
        }

        $this->configuration = $config;
    }

    /**
     * @return array
     */
    public function getConfiguration($primaryKey = '')
    {
        if (empty($primaryKey)) {
            return $this->configuration;
        }

        if (!array_key_exists($primaryKey, $this->configuration)) {
            throw new InvalidConfigException('Invalid primary key in configuration array');
        }

        return $this->configuration[$primaryKey];
    }
}
