<?php
namespace ZohoBooksAL\Hydrator;

use DI\FactoryInterface;
use ZohoBooksAL\Code\Annotation\Exception\InvalidAttributeValueException;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\Metadata\ClassMetadata;

class EntityHydrator
{
    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * EntityHydrator constructor.
     *
     * @param ClassMetadata $metadata
     * @param FactoryInterface $factory
     */
    public function __construct(ClassMetadata $metadata, FactoryInterface $factory)
    {
        $this->metadata = $metadata;
        $this->factory = $factory;
    }

    /**
     * @param EntityInterface $entity
     * @return array
     * @throws Exception\RuntimeException
     */
    public function extract(EntityInterface $entity, $ignoreRequired = false)
    {
        $data = [];
        foreach($this->metadata->getProperties() as $property) {
            $value = $entity->{$property->getGetter()}();
            if ($property->isReadonly()) {
                continue;
            }

            $extractor = $property->getExtractor();
            $result = $extractor($value);

            if ($ignoreRequired !== false && $property->isRequired() && (is_null($result) || empty($result))) {
                throw new InvalidAttributeValueException($property->getField() .
                                                         ' required to fill it on. It is must not be null.');
            }

            if ($property->isOneToMany()) {
                $entityMetadata = $property->getTargetEntity();
                $hydrator = $this->factory->make(EntityHydrator::class, ['metadata'=>$entityMetadata]);

                $newResult = [];
                foreach ($result as $resultEntity) {
                    $newResult[] = $hydrator->extract($resultEntity);
                }
                $result = $newResult;
            }

            if (is_null($result)) continue;
            $data[$property->getField()] = $result;
        }

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  EntityInterface $entity
     *
     * @return EntityInterface
     */
    public function hydrate(array $data, EntityInterface $entity)
    {
        foreach($this->metadata->getProperties() as $property) {
            $propertyHydrator = $property->getHydrator();
            $propertyHydratedValue = $propertyHydrator($data);
            if ($property->isOneToMany()) {
                $splObjectStorage = $entity->{$property->getGetter()}();
                $splObjectStorage->removeAll($splObjectStorage);

                $entityMetadata = $property->getTargetEntity();
                $hydrator = $this->factory
                                 ->make(EntityHydrator::class, ['metadata' => $entityMetadata]);

                $className = $entityMetadata->getName();

                if (!is_array($propertyHydratedValue)) {
                    if (empty($propertyHydratedValue)) {
                        $entity->{$property->getSetter()}(new \SplObjectStorage());
                    } else {
                        $entity->{$property->getSetter()}($propertyHydratedValue);
                    }
                }

                foreach ($propertyHydratedValue as $propertyHydratedValueItem) {
                    
                    $newHydratedEntity = $hydrator->hydrate((array)$propertyHydratedValueItem,
                                                            $this->factory->make($className));
                    
                    $entity->{$property->getHandler()}($newHydratedEntity);
                }
            } else {
                if (is_null($propertyHydratedValue)) continue;
                $entity->{$property->getHandler()}($propertyHydratedValue);
            }
        }

        return $entity;
    }
}
