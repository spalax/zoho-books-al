<?php
namespace ZohoBooksAL\Persister;

use DI\FactoryInterface;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\EntityManager;
use ZohoBooksAL\Hydrator\EntityHydrator;
use ZohoBooksAL\Mapper\MapperInterface;
use ZohoBooksAL\Metadata\ClassMetadata;
use ZohoBooksAL\UnitOfWork;

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
     * @var PersistedEntityTracker
     */
    protected $entityTracker;

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
     * @param PersistedEntityTracker $entityTracker
     */
    public function __construct(MapperInterface $mapper,
                                ClassMetadata $classMetadata,
                                PersistedEntityTracker $entityTracker,
                                FactoryInterface $factory)
    {
        $this->mapper = $mapper;
        $this->factory = $factory;
        $this->entityTracker = $entityTracker;
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
        
        $this->entityTracker->track($entity, $hydrator->extract($entity));
        return $entity;
    }

    /**
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity)
    {
        $hydrator = $this->factory->make(EntityHydrator::class, ['metadata'=>$this->classMetadata]);
        $values = $hydrator->extract($entity);
        
        if ($this->entityTracker->isNew($entity)) {
            $item = $this->mapper
                         ->create($this->classMetadata->getServiceCollectionPath(),
                                  $this->classMetadata->getServiceCollectionItemName(),
                                  $values);

            $hydrator->hydrate($item, $entity);
            $this->entityTracker->track($entity, $hydrator->extract($entity));
        } else if (($diffValues = $this->entityTracker->getEntitiesDiff($entity, $values)) !== false) {
            $item = $this->mapper
                         ->update($this->classMetadata->getServiceCollectionPath(),
                                  $this->classMetadata->getServiceCollectionItemName(),
                                  $values[$this->classMetadata->getPrimary()->getField()],
                                  $diffValues);
            
            $hydrator->hydrate($item, $entity);
            $this->entityTracker->track($entity, $hydrator->extract($entity));
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
            $this->entityTracker->track($entity, $item);
            $entities[] = $entity;
        }

        return $entities;
    }
}
