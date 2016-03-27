<?php
namespace ZohoBooksAL\Entity;

/**
 * Organization entity
 *
 * @ZOHO\Service(collectionPath="/organizations",
 *               collectionName="organizations",
 *               collectionItemName="organization")
 */
class Organization implements EntityInterface
{
    /**
     * @var string
     *
     * @ZOHO\Id
     * @ZOHO\Column(name="organization_id")
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

