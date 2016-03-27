<?php
namespace ZohoBooksAL;

use DI\FactoryInterface;
use ZohoBooksAL\Configuration\ConfigurationInterface;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\Metadata\MetadataCollection;
use ZohoBooksAL\Repository\BasicRepository;
use ZohoBooksAL\Repository\RepositoryInterface;

class EntityManager implements EntityManagerInterface
{
    /**
     * @var UnitOfWork
     */
    protected $unitOfWork = null;

    /**
     * @var MetadataCollection
     */
    protected $metadataCollection = null;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * EntityManager constructor.
     *
     * @param ConfigurationInterface $configuration
     * @param UnitOfWork $unitOfWork
     * @param MetadataCollection $metadataCollection
     * @param FactoryInterface $factory
     */
    public function __construct(ConfigurationInterface $configuration,
                                UnitOfWork $unitOfWork,
                                MetadataCollection $metadataCollection,
                                FactoryInterface $factory)
    {
        $this->unitOfWork = $unitOfWork;
        $this->metadataCollection = $metadataCollection;
        $this->configuration = $configuration;
        $this->factory = $factory;
    }

    /**
     * Return specific repository
     * to get entities
     *
     * @return RepositoryInterface
     */
    public function getRepository($entityName)
    {
        return $this->factory->make(BasicRepository::class,
                                    ['entityName'=>$entityName,
                                     'unitOfWork'=>$this->unitOfWork]);
    }

    /**
     * @param string $entityName
     * @param mixed $id
     * @return null | object
     */
    public function find($entityName, $id)
    {
        $persister = $this->unitOfWork->getEntityPersister($entityName);
        return $persister->load($id);
    }

    /**
     * Persist newly created entity, which mean that
     * it will be created on remote API after
     * flush will be called
     *
     * @param EntityInterface $entity
     *
     * @return void
     */
    public function persist(EntityInterface $entity)
    {
        $this->unitOfWork->persist($entity);
    }

    /**
     * Flushing all persisted entities to the
     * remote API
     *
     * @return void
     */
    public function flush()
    {
        $this->unitOfWork->commit();
    }
}
