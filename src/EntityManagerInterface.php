<?php
namespace ZohoBooksAL;


use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\Repository\RepositoryInterface;

interface EntityManagerInterface
{
    /**
     * Return specific repository
     * to get entities
     *
     * @return RepositoryInterface
     */
    public function getRepository($repositoryClass);

    /**
     * Persist newly created entity, which mean that
     * it will be created on remote API after
     * flush will be called
     *
     * @param EntityInterface $entityInterface
     *
     * @return void
     */
    public function persist(EntityInterface $entity);

    /**
     * Flushing all persisted entities to the
     * remote API
     *
     * @return void
     */
    public function flush();
}
