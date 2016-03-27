<?php
namespace ZohoBooksAL\Entity;

/**
 * TimeEntry entity
 *
 * @ZOHO\Service(collectionPath="/projects/timeentries",
 *               collectionName="time_entries",
 *               collectionItemName="time_entry")
 */
class TimeEntry implements EntityInterface
{
    /**
     * @var string
     *
     * @ZOHO\Id
     * @ZOHO\Column(name="time_entry_id")
     */
    protected $id;

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
}
