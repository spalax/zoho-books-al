<?php
namespace ZohoBooksAL\Metadata\Collector;

use ICanBoogie\Inflector;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Scanner\ClassScanner;
use Zend\Code\Scanner\PropertyScanner;
use ZohoBooksAL\Code\Annotation;
use Zend\Code\Scanner\DirectoryScanner;
use ZohoBooksAL\Metadata\ClassMetadata;
use ZohoBooksAL\Metadata\Collector\Exception\RuntimeException;

class EntityCollector implements CollectorInterface
{
    /**
     * @var DirectoryScanner
     */
    protected $directoryScanner = null;

    /**
     * @var AnnotationManager
     */
    protected $annotationManager = null;

    /**
     * @var array
     */
    protected $classes = array();

    /**
     * @var Inflector
     */
    protected $propertyNameInflector = null;

    /**
     * EntityCollector constructor.
     *
     * @param AnnotationManager $manager
     * @param DirectoryScanner $scanner
     * @param Inflector $inflector
     */
    public function __construct(AnnotationManager $manager,
                                DirectoryScanner $scanner,
                                Inflector $inflector)
    {
        $this->annotationManager = $manager;
        $this->directoryScanner = $scanner;
        $this->propertyNameInflector = $inflector;
    }

    /**
     * @param ClassScanner $classScanner
     * @return array
     */
    protected function processClassAnnotations(ClassScanner $classScanner)
    {
        $result = array();
        $result['name'] = $classScanner->getName();
        $annotations = $classScanner->getAnnotations($this->annotationManager);
        if (empty($annotations)) return [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Annotation\Service) {
                $result['collectionName'] = $annotation->getCollectionName();
                $result['collectionPath'] = $annotation->getCollectionPath();
                $result['collectionItemName'] = $annotation->getCollectionItemName();
            } else if ($annotation instanceof Annotation\Entity) {
                $result['repository'] = $annotation->getRepository();
            }
        }
        return $result;
    }

    /**
     * @param ClassScanner $classScanner
     * @param PropertyScanner $propertyScanner
     * @return string
     */
    protected function detectPropertyGet(ClassScanner $classScanner, PropertyScanner $propertyScanner)
    {
        foreach($classScanner->getMethodNames() as $methodName) {
            if (strtolower($methodName) == strtolower('get'.$propertyScanner->getName()) ||
                strtolower($methodName) == strtolower('is'.$propertyScanner->getName())) {
                return $methodName;
            }
        }

        throw new RuntimeException("Could not found get for property ".$propertyScanner->getName());
    }

    /**
     * @param ClassScanner $classScanner
     * @param PropertyScanner $propertyScanner
     * @return string
     */
    protected function detectPropertySet(ClassScanner $classScanner, PropertyScanner $propertyScanner)
    {
        foreach($classScanner->getMethodNames() as $methodName) {
            if (strtolower($methodName) == strtolower('set'.$propertyScanner->getName())) {
                return $methodName;
            }
        }

        throw new RuntimeException("Could not found set for property ".$propertyScanner->getName());
    }

    /**
     * @param ClassScanner $classScanner
     * @param PropertyScanner $propertyScanner
     * @return string
     */
    protected function detectPropertyAdd(ClassScanner $classScanner, PropertyScanner $propertyScanner)
    {
        foreach($classScanner->getMethodNames() as $methodName) {
            $singularName = $this->propertyNameInflector->singularize($propertyScanner->getName());
            if (strtolower($methodName) == strtolower('add'.$singularName)) {
                return $methodName;
            }
        }

        throw new RuntimeException("Could not found add handler for property ".$propertyScanner->getName());
    }

    /**
     * @param ClassScanner $classScanner
     * @param ClassScanner[] $allClassScanners
     * @return array
     */
    protected function processPropertiesAnnotations(ClassScanner $classScanner, array $allClassScanners)
    {
        $result = array();
        /* @var $propertyScanner \Zend\Code\Scanner\PropertyScanner */
        foreach ($classScanner->getProperties() as $propertyScanner) {
            $propertyArr = array();
            foreach($propertyScanner->getAnnotations($this->annotationManager) as $annotation) {
                if ($annotation instanceof Annotation\Column) {
                    $propertyArr = array_merge($propertyArr, $this->handleColumn($annotation,
                                                                                 $propertyScanner,
                                                                                 $classScanner));
                } else if ($annotation instanceof Annotation\Id) {
                    $propertyArr['primary'] = true;
                } else if ($annotation instanceof Annotation\OneToMany) {
                    $propertyArr = array_merge($propertyArr,
                                               $this->handleOneToManyAnnotation($annotation,
                                                                                $propertyScanner,
                                                                                $classScanner,
                                                                                $allClassScanners));
                }
            }

            if (!empty($propertyArr)) {
                $result['properties'][] = $propertyArr;
            }
        }

        return $result;
    }

    /**
     * @param Annotation\Column $annotation
     * @param PropertyScanner $propertyScanner
     * @param ClassScanner $classScanner
     * @return array
     */
    protected function handleColumn(Annotation\Column $annotation,
                                    PropertyScanner $propertyScanner,
                                    ClassScanner $classScanner)
    {
        $propertyArr = array();

        $propertyArr['name'] = $propertyScanner->getName();
        $propertyArr['field'] = $annotation->getName();
        $propertyArr['required'] = $annotation->isRequired();
        $propertyArr['readonly'] = $annotation->isReadonly();
        $propertyArr['hydrator'] = $annotation->getHydrator();
        $propertyArr['extractor'] = $annotation->getExtractor();
        $propertyArr['handler'] = $this->detectPropertySet($classScanner, $propertyScanner);
        $propertyArr['setter'] = $propertyArr['handler'];
        $propertyArr['getter'] = $this->detectPropertyGet($classScanner, $propertyScanner);

        return $propertyArr;
    }

    /**
     * @param Annotation\OneToMany $annotation
     * @param PropertyScanner $propertyScanner
     * @param ClassScanner $classScanner
     * @param ClassScanner[] $allClassScanners
     * @return array
     * @throws Exception\RuntimeException
     */
    protected function handleOneToManyAnnotation(Annotation\OneToMany $annotation,
                                                 PropertyScanner $propertyScanner,
                                                 ClassScanner $classScanner,
                                                 array $allClassScanners)
    {
        $targetEntity = $annotation->getTargetEntity();
        $propertyArr = array();

        $propertyArr['name'] = $propertyScanner->getName();
        $propertyArr['field'] = $annotation->getName();
        $propertyArr['extractor'] = $annotation->getExtractor();
        $propertyArr['hydrator'] = $annotation->getHydrator();
        $propertyArr['handler'] = $this->detectPropertyAdd($classScanner, $propertyScanner);
        $propertyArr['getter'] = $this->detectPropertyGet($classScanner, $propertyScanner);
        $propertyArr['setter'] = $this->detectPropertySet($classScanner, $propertyScanner);

        $ref = new \ReflectionClass($classScanner->getName());
        if (!$ref->newInstance()->{$propertyArr['getter']}() instanceof \SplObjectStorage) {
            throw new RuntimeException("Invalid data type returned from getter in OneToMany relationship,
                                        must return SplObjectStorage");
        }

        /* @var $scanner ClassScanner  */
        foreach ($allClassScanners as $scanner) {
            if ($scanner->getName() == $targetEntity) {
                $collectedData = $this->collectDataForClass($scanner, $allClassScanners);

                // If nothing could be collected, so skip this class... it is not an entity
                if (!$collectedData) {
                    continue;
                }

                $propertyArr['targetEntity'] = new ClassMetadata($collectedData);
                return $propertyArr;
            }
        }

        throw new RuntimeException("Invalid targetEntity this Entity could not be found by entity scanner");
    }


    /**
     * @param ClassScanner $classScanner
     * @param ClassScanner[] $allClasses
     * @return array | false
     */
    protected function collectDataForClass(ClassScanner $classScanner, array $allClasses)
    {
        if ($classScanner->isInterface() || $classScanner->isAbstract()) return false;

        $classAnnotations = $this->processClassAnnotations($classScanner);
        $propertiesAnnotations = $this->processPropertiesAnnotations($classScanner, $allClasses);
        $classArr = array('properties'=>array());

        if (empty($classAnnotations) && empty($propertiesAnnotations)) {
            return false;
        }

        return array_merge($classArr, $classAnnotations, $propertiesAnnotations);
    }

    /**
     * @return array
     */
    public function collect()
    {
        $classes = array();

        /* @var $classesToScan ClassScanner[] */
        $classesToScan = $this->directoryScanner->getClasses();
        
        /* @var $classScanner \Zend\Code\Scanner\ClassScanner */
        foreach ($classesToScan as $classScanner) {
            if (!($classArr = $this->collectDataForClass($classScanner, $classesToScan))) {
                continue;
            }

            $classes[$classScanner->getName()] = $classArr;
        }

        return $classes;
    }
}
