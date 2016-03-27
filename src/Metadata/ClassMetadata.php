<?php
namespace ZohoBooksAL\Metadata;

use ZohoBooksAL\Metadata\Exception\InvalidArgumentException;

class ClassMetadata
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $serviceCollectionName = '';

    /**
     * @var string
     */
    protected $serviceCollectionItemName = '';

    /**
     * @var string
     */
    protected $serviceCollectionPath = '';

    /**
     * @var string
     */
    protected $repository = '';

    /**
     * @var null
     */
    protected $primaryProperty = null;

    /**
     * @var PropertyMetadata[]
     */
    protected $properties = array();

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!array_key_exists('name', $data)) {
            throw new InvalidArgumentException('Name must be present in data array');
        }
        $this->setName($data['name']);

        if (array_key_exists('collectionName', $data)) {
            $this->setServiceCollectionName($data['collectionName']);
        }

        if (array_key_exists('collectionItemName', $data)) {
            $this->setServiceCollectionItemName($data['collectionItemName']);
        }

        if (array_key_exists('repository', $data)) {
            $this->setRepository($data['repository']);
        }

        if (array_key_exists('collectionPath', $data)) {
            $this->setServiceCollectionPath($data['collectionPath']);
        }

        if (!array_key_exists('properties', $data) || !is_array($data['properties'])) {
            throw new InvalidArgumentException('Properties must be present in data array');
        }

        foreach ($data['properties'] as $property) {
            $prop = new PropertyMetadata($property);
            if ($prop->getPrimary()) {
                $this->primaryProperty = $prop;
            }
            $this->addProperty($prop);
        }
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PropertyMetadata | null
     */
    public function getPrimary()
    {
        return $this->primaryProperty;
    }

    /**
     * @param array $properties
     */
    protected function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param PropertyMetadata $property
     */
    protected function addProperty(PropertyMetadata $property)
    {
        array_push($this->properties, $property);
    }

    /**
     * @param string $name
     * @return null|PropertyMetadata
     */
    public function getProperty($name)
    {
        foreach ($this->properties as $property) {
            if ($property->getName() == $name) {
                return $property;
            }
        }
        return null;
    }

    /**
     * @return PropertyMetadata[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $repository
     */
    protected function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $serviceCollectionPath
     */
    protected function setServiceCollectionPath($serviceCollectionPath)
    {
        $this->serviceCollectionPath = $serviceCollectionPath;
    }

    /**
     * @return string
     */
    public function getServiceCollectionPath()
    {
        return $this->serviceCollectionPath;
    }

    /**
     * @return boolean
     */
    public function isPersistable()
    {
        return !empty($this->serviceCollectionPath);
    }

    /**
     * @return string
     */
    public function getServiceCollectionName()
    {
        return $this->serviceCollectionName;
    }

    /**
     * @param string $serviceCollectionName
     */
    protected function setServiceCollectionName($serviceCollectionName)
    {
        $this->serviceCollectionName = $serviceCollectionName;
    }

    /**
     * @return string
     */
    public function getServiceCollectionItemName()
    {
        return $this->serviceCollectionItemName;
    }

    /**
     * @param string $serviceCollectionItemName
     */
    protected function setServiceCollectionItemName($serviceCollectionItemName)
    {
        $this->serviceCollectionItemName = $serviceCollectionItemName;
    }
}
