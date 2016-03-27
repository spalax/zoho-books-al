<?php

namespace ZohoBooksAL\Repository;

use ZohoBooksAL\UnitOfWork;

abstract class RepositoryAbstract implements RepositoryInterface
{
    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    /**
     * RepositoryAbstract constructor.
     *
     * @param string $entityName
     * @param UnitOfWork $unitOfWork
     */
    public function __construct($entityName, UnitOfWork $unitOfWork)
    {
        $this->entityName = $entityName;
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * @param mixed $id
     * @return object | null
     */
    public function find($id)
    {
        $persister = $this->unitOfWork->getEntityPersister($this->entityName);
        return $persister->load($id);
    }
}
