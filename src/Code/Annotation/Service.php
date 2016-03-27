<?php
namespace ZohoBooksAL\Code\Annotation;
use Zend\Code\Annotation\AnnotationInterface;
use ZohoBooksAL\Entity\EntityInterface;
use ZohoBooksAL\Metadata\ClassMetadata;

class Service implements AnnotationInterface
{
    /**
     * @var string
     */
    protected $collectionName = '';

    /**
     * @var string
     */
    protected $collectionItemName = '';

    /**
     * @var string
     */
    protected $collectionPath = '';

    /**
     * Initialize
     *
     * @param string $content
     */
    public function initialize($content)
    {
        if (preg_match("/collectionName\s*?=\s*?[\"|\'](?P<name>.+?)[\"|\']/", $content, $matches)) {
            $this->collectionName = $matches['name'];
        }

        if (preg_match("/collectionItemName\s*?=\s*?[\"|\'](?P<name>.+?)[\"|\']/", $content, $matches)) {
            $this->collectionItemName = $matches['name'];
        }

        if (preg_match("/collectionPath\s*?=\s*?[\"|\'](?P<path>.+?)[\"|\']/", $content, $matches)) {
            $this->collectionPath = $matches['path'];
        }

        assert(!empty($this->collectionPath), 'collectionPath must be defined for entity');
        assert(!empty($this->collectionName), 'collectionName must be defined for entity');
        assert(!empty($this->collectionItemName), 'itemName must be defined for entity');
    }

    public function getCollectionPath()
    {
        return $this->collectionPath;
    }

    public function getCollectionName()
    {
        return $this->collectionName;
    }

    public function getCollectionItemName()
    {
        return $this->collectionItemName;
    }
}
