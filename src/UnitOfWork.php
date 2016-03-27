<?php
namespace ZohoBooksAL;

use DI\FactoryInterface;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\Mapper\MapperInterface;
use ZohoBooksAL\Metadata\ClassMetadata;
use ZohoBooksAL\Metadata\MetadataCollection;
use ZohoBooksAL\Persister;

class UnitOfWork
{
    const PERSISTED_NEW = 'new';
    const PERSISTED_OLD = 'old';

    /**
     * @var array
     */
    protected $persisters = array();

    /**
     * @var ClassMetadata[]
     */
    protected $metadataCollection = null;

    /**
     * @var array
     */
    protected $persisted = array();

    /**
     * @var MapperInterface
     */
    protected $mapper = null;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * UnitOfWork constructor.
     *
     * @param MetadataCollection $metadataCollection
     * @param MapperInterface $mapper
     * @param FactoryInterface $factory
     */
    public function __construct(MetadataCollection $metadataCollection,
                                MapperInterface $mapper,
                                FactoryInterface $factory)
    {
        $this->metadataCollection = $metadataCollection;
        $this->mapper = $mapper;
        $this->factory = $factory;
        $this->persisted = array();
    }

    /**
     * @param string $entityName
     * @return Persister\PersisterInterface
     */
    public function getEntityPersister($entityName)
    {
        if (!array_key_exists($entityName, $this->persisters)) {
            $persister = $this->factory->make(Persister\BasicEntityPersister::class,
                                              ['mapper'=>$this->mapper,
                                               'classMetadata' => $this->metadataCollection
                                                                       ->getClassMetadata($entityName)]);

            $this->persisters[$entityName] = $persister;
        }

        if (!$this->metadataCollection
                  ->getClassMetadata($entityName)->isPersistable()) {
            throw new Exception\NotPersistableEntityException('Entity '.$entityName.' is not persistable'.
                                                                ' and must be used as a part of parent entity!');
        }

        return $this->persisters[$entityName];
    }

    /**
     * @param EntityInterface $entity
     */
    public function persist(EntityInterface $entity)
    {
        $key = spl_object_hash($entity);
        $this->persisted[$key] = $entity;
    }

    public function commit()
    {
        foreach ($this->persisted as $k=>$entity) {
            $reflection = $this->factory->make(\ReflectionObject::class, ['argument' => $entity]);
            $persister = $this->getEntityPersister($reflection->getName());
            $persister->save($entity);

            unset($this->persisted[$k]);
        }
    }
}
