<?php
namespace ZohoBooksAL\Configuration;

interface ConfigurationInterface
{
    /**
     * @return string
     */
    public function getAuthToken();

    /**
     * @return string
     */
    public function getServiceUri();
}
