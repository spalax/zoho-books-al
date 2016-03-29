<?php
namespace ZohoBooksAL\Entity;

/**
 * Custom field entity
 */
class CustomField implements EntityInterface
{
    /**
     * @var string
     *
     * @ZOHO\Column(name="customfield_id")
     */
    protected $id;

    /**
     * @var number
     *
     * @ZOHO\Column(name="label")
     */
    protected $label;

    /**
     * @var mixed
     *
     * @ZOHO\Column(name="value", required="true")
     */
    protected $value;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return number
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param number $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
