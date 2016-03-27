<?php
namespace ZohoBooksAL\Repository;

interface RepositoryInterface
{
    /**
     * Find entity by it is identifier
     *
     * @param mixed $id
     *
     * @return object | null
     */
    public function find($id);

    /**
     * @param array $criteria
     * @param int $offset
     * @param int | null $limit
     *
     * @return mixed
     */
    public function findAll(array $criteria = array(), $offset = 0, $limit = null);
}
