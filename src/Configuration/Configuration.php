<?php
namespace ZohoBooksAL\Configuration;

use ZohoBooksAL\Configuration\Reader\ReaderInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Configuration constructor.
     *
     * @param ReaderInterface $configurationReader
     */
    public function __construct(ReaderInterface $configurationReader)
    {
        $this->data = $configurationReader->getConfiguration('zoho-books');

        if (empty($this->data['authToken'])) {
            throw new InvalidOptionException("Missing mandatory key 'authToken' in configuration,
            to obtain this token you should read https://www.zoho.com/books/api/v3/");
        }

        if (empty($this->data['serviceUri'])) {
            throw new InvalidOptionException("Missing mandatory key 'serviceUri' in configuration,
            to decide which serviceUri must be defined please take a look at https://www.zoho.com/books/api/v3/");
        }
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->data['authToken'];
    }

    /**
     * @return mixed
     */
    public function getServiceUri()
    {
        return $this->data['serviceUri'];
    }

    /**
     * @return array
     */
    public function getEntitiesPaths()
    {
        return $this->data['entitiesPaths'];
    }
}
