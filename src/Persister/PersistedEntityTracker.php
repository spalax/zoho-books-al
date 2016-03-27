<?php
namespace ZohoBooksAL\Persister;

use DI\FactoryInterface;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\EntityManager;
use ZohoBooksAL\Hydrator\EntityHydrator;
use ZohoBooksAL\Mapper\MapperInterface;
use ZohoBooksAL\Metadata\ClassMetadata;
use ZohoBooksAL\UnitOfWork;

class PersistedEntityTracker
{
    /**
     * @var array
     */
    protected $loadedEntityData;

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function isNew(EntityInterface $entity)
    {
        $id = spl_object_hash($entity);
        return !array_key_exists($id, $this->loadedEntityData);
    }

    /**
     * @param EntityInterface $entity
     * @param array $data
     *
     * @return array|bool
     */
    public function getEntitiesDiff(EntityInterface $entity, array $data)
    {
        $id = spl_object_hash($entity);
        
        if (array_key_exists($id, $this->loadedEntityData)) {
            $diff = $this->arrayRecursiveDiff($data, unserialize($this->loadedEntityData[$id]));
            if (!empty($diff)) {
                return $diff;
            }
        }

        return false;
    }

    /**
     * @param array $aArray1
     * @param array $aArray2
     *
     * @return array
     */
    private function arrayRecursiveDiff(array $aArray1, array $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }

    /**
     * @param EntityInterface $entity
     * @param array $data
     */
    public function track(EntityInterface $entity, array $data)
    {
        $id = spl_object_hash($entity);
        $this->loadedEntityData[$id] = serialize($data);
    }
}
