<?php
namespace ZohoBooksAL\Persister;

use ZohoBooksAL\Entity\EntityInterface;

interface PersisterInterface
{
    /**
     * @param string | number $identifier
     * @return null | object
     */
    public function load($identifier);

    /**
     * @param array $criteria [OPTIONAL]
     * @param int $offset [OPTIONAL]
     * @param int $limit [OPTIONAL]
     * @return object[]
     */
    public function loadAll(array $criteria = [], $offset = null, $limit = null);

    /**
     * @param EntityInterface $entity
     * @return mixed
     */
    public function save(EntityInterface $entity);
}
