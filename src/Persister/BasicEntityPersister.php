<?php
namespace ZohoBooksAL\Persister;

use DI\FactoryInterface;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\Hydrator\EntityHydrator;
use ZohoBooksAL\Mapper\MapperInterface;
use ZohoBooksAL\Metadata\ClassMetadata;

class BasicEntityPersister implements PersisterInterface
{
    /**
     * @var MapperInterface
     */
    protected $mapper;

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * BasicEntityPersister constructor.
     *
     * @param MapperInterface $mapper
     * @param ClassMetadata $classMetadata
     * @param FactoryInterface $factory
     */
    public function __construct(MapperInterface $mapper,
                                ClassMetadata $classMetadata,
                                FactoryInterface $factory)
    {
        $this->mapper = $mapper;
        $this->factory = $factory;
        $this->classMetadata = $classMetadata;
    }

    /**
     * @param string | number $id
     * @return object | null
     */
    public function load($identifier)
    {
        $data = $this->mapper->fetchOne($this->classMetadata->getServiceCollectionPath(),
                                        $this->classMetadata->getServiceCollectionItemName(),
                                        $identifier);
        
        if (is_null($data)) {
            return null;
        }

        $entityName = $this->classMetadata->getName();
        $hydrator = $this->factory->make(EntityHydrator::class, ['metadata'=>$this->classMetadata]);

        $entity = $hydrator->hydrate($data, $this->factory->make($entityName));
        return $entity;
    }

    /**
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity)
    {
        $hydrator = $this->factory->make(EntityHydrator::class, ['metadata'=>$this->classMetadata]);
        $values = $hydrator->extract($entity);
        $id = $entity->{$this->classMetadata->getPrimary()->getGetter()}();

        if (empty($id)) {
            $item = $this->mapper
                         ->create($this->classMetadata->getServiceCollectionPath(),
                                  $this->classMetadata->getServiceCollectionItemName(),
                                  $values);

            $hydrator->hydrate($item, $entity);
        } else {
            $item = $this->mapper
                         ->update($this->classMetadata->getServiceCollectionPath(),
                                  $this->classMetadata->getServiceCollectionItemName(),
                                  $values[$this->classMetadata->getPrimary()->getField()],
                                  $values);
            
            $hydrator->hydrate($item, $entity);
        }
    }

    /**
     * @param array $params [OPTIONAL]
     * @param int $offset [OPTIONAL]
     * @param int $limit [OPTIONAL]
     * @return array
     */
    public function loadAll(array $params = array(), $offset = null, $limit = null)
    {
        $items = $this->mapper->fetchAll($this->classMetadata->getServiceCollectionPath(),
                                         $this->classMetadata->getServiceCollectionItemName(),
                                         $params, $offset, $limit);

        $entityName = $this->classMetadata->getName();

        $entities = [];

        foreach ($items as $item) {
            $hydrator = $this->factory->make(EntityHydrator::class, ['metadata'=>$this->classMetadata]);
            $entity = $hydrator->hydrate($item, $this->factory->make($entityName));
            $entities[] = $entity;
        }

        return $entities;
    }
}
