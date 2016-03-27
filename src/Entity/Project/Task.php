<?php
namespace ZohoBooksAL\Entity\Project;
use ZohoBooksAL\Entity\EntityInterface;

/**
 * TimeEntry entity
 */
class Task implements EntityInterface
{
    /**
     * @var string
     *
     * @ZOHO\Column(name="task_id")
     */
    protected $id;

    /**
     * @var number
     *
     * @ZOHO\Column(name="project_id", required="true")
     */
    protected $projectId;

    /**
     * @var string
     *
     * @ZOHO\Column(name="task_name", required="true")
     */
    protected $name;

    /**
     * @var string
     *
     * @ZOHO\Column(name="description", required="true")
     */
    protected $description;

    /**
     * @var boolean
     *
     * @ZOHO\Column(name="is_billable", required="true")
     */
    protected $billable;

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
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param number $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function isBillable()
    {
        return $this->billable;
    }

    /**
     * @param boolean $billable
     */
    public function setBillable($billable)
    {
        $this->billable = $billable;
    }
}
