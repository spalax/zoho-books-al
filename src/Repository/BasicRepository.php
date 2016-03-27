<?php

namespace ZohoBooksAL\Repository;

class BasicRepository extends RepositoryAbstract
{
    /**
     * @param array $criteria
     * @param int $offset
     * @param null | int $limit
     * @return array
     */
    public function findAll(array $criteria = array(), $offset = 0, $limit = null)
    {
        $persister = $this->unitOfWork->getEntityPersister($this->entityName);
        return $persister->loadAll($criteria, $offset, $limit);
    }
}
