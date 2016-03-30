<?php
namespace ZohoBooksAL\Metadata;

use ZohoBooksAL\Metadata\Exception\InvalidArgumentException;

class PropertyMetadata
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $field = '';

    /**
     * @var \Closure
     */
    protected $extractor = null;

    /**
     * @var \Closure
     */
    protected $hydrator = null;

    /**
     * @var ClassMetadata
     */
    protected $targetEntity = null;

    /**
     * @var string
     */
    protected $handler = '';

    /**
     * @var string
     */
    protected $getter = '';

    /**
     * @var string
     */
    protected $setter = '';

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var bool
     */
    protected $readonly;

    /**
     * @var bool
     */
    protected $primary = false;

    /**
     * @return string
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * @param string $setter
     */
    protected function setSetter($setter)
    {
        $this->setter = $setter;
    }



    /**
     * @param string $getter
     */
    protected function setGetter($getter)
    {
        $this->getter = $getter;
    }

    /**
     * @return string
     */
    public function getGetter()
    {
        return $this->getter;
    }

    /**
     * @param boolean $primary
     */
    protected function setPrimary($primary)
    {
        $this->primary = $primary;
    }

    /**
     * @return boolean
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!array_key_exists('name', $data)) {
            throw new InvalidArgumentException("Name must present in property data");
        }
        $this->setName($data['name']);

        if (array_key_exists('readonly', $data)) {
            $this->setReadonly($data['readonly']);
        }

        if (array_key_exists('required', $data)) {
            $this->setRequired($data['required']);
        }

        if (!array_key_exists('field', $data)) {
            throw new InvalidArgumentException("Field must present in property data");
        }
        $this->setField($data['field']);

        if (!array_key_exists('handler', $data)) {
            throw new InvalidArgumentException("Handler must present in property data");
        }
        $this->setHandler($data['handler']);

        if (!array_key_exists('getter', $data)) {
            throw new InvalidArgumentException("Getter must present in property data");
        }
        $this->setGetter($data['getter']);

        if (!array_key_exists('setter', $data)) {
            throw new InvalidArgumentException("Setter must present in property data");
        }
        $this->setSetter($data['setter']);

        if (!array_key_exists('hydrator', $data) || !($data['hydrator'] instanceof \Closure)) {
            throw new InvalidArgumentException("Extractor must present and callable in property data");
        }
        $this->setHydrator($data['hydrator']);

        if (!array_key_exists('extractor', $data) || !($data['extractor'] instanceof \Closure)) {
            throw new InvalidArgumentException("Serializer must present and callable in property data");
        }
        $this->setExtractor($data['extractor']);

        if (array_key_exists('primary', $data) && $data['primary'] === true) {
            $this->setPrimary(true);
        }

        if (array_key_exists('targetEntity', $data) && $data['targetEntity'] instanceof ClassMetadata) {
            $this->setTargetEntity($data['targetEntity']);
        }
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Closure $extractor
     */
    protected function setExtractor(\Closure $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @return \Closure
     */
    public function getExtractor()
    {
        return $this->extractor;
    }

    /**
     * @param \Closure $serializer
     */
    protected function setHydrator(\Closure $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @return \Closure
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param string $field
     */
    protected function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $handler
     */
    protected function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return bool
     */
    public function isOneToMany()
    {
        return $this->targetEntity == null ? false : true ;
    }

    /**
     * @param ClassMetadata $targetEntity
     */
    protected function setTargetEntity(ClassMetadata $targetEntity)
    {
        $this->targetEntity = $targetEntity;
    }

    /**
     * @return ClassMetadata
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     */
    protected function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    /**
     * @param boolean $readonly
     */
    protected function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }
}
