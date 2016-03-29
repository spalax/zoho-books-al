<?php
namespace ZohoBooksAL\Entity;

/**
 * TimeEntry entity
 *
 * @ZOHO\Service(collectionPath="/projects/timeentries",
 *               collectionName="time_entries",
 *               collectionItemName="time_entries")
 */
class ProjectTimeEntry implements EntityInterface
{
    /**
     * @var string
     *
     * @ZOHO\Id
     * @ZOHO\Column(name="time_entry_id")
     */
    protected $id;

    /**
     * @var number
     *
     * @ZOHO\Column(name="project_id", required="true")
     */
    protected $projectId;

    /**
     * @var number
     *
     * @ZOHO\Column(name="task_id", required="true")
     */
    protected $taskId;

    /**
     * @var number
     *
     * @ZOHO\Column(name="user_id", required="true")
     */
    protected $userId;

    /**
     * @var \DateTime
     *
     * @ZOHO\Column(name="log_date", type="DateTime", format="Y-m-d", required="true")
     */
    protected $logDate;

    /**
     * @var string
     *
     * @ZOHO\Column(name="log_time", required="true")
     */
    protected $logTime;

    /**
     * @var boolean
     *
     * @ZOHO\Column(name="is_billable")
     */
    protected $isBillable = true;

    /**
     * @var string
     *
     * @ZOHO\Column(name="notes")
     */
    protected $notes;

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
     * @return number
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param number $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return number
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param number $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return \DateTime
     */
    public function getLogDate()
    {
        return $this->logDate;
    }

    /**
     * @param \DateTime $logDate
     */
    public function setLogDate($logDate)
    {
        $this->logDate = $logDate;
    }

    /**
     * @return string
     */
    public function getLogTime()
    {
        return $this->logTime;
    }

    /**
     * @param string $logTime
     */
    public function setLogTime($logTime)
    {
        $this->logTime = $logTime;
    }

    /**
     * @return boolean
     */
    public function isIsBillable()
    {
        return $this->isBillable;
    }

    /**
     * @param boolean $isBillable
     */
    public function setIsBillable($isBillable)
    {
        $this->isBillable = $isBillable;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }
}
