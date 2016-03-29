<?php
namespace ZohoBooksAL\Entity;

/**
 * User entity
 *
 * @ZOHO\Service(collectionPath="/users",
 *               collectionName="users",
 *               collectionItemName="users")
 */
class User implements EntityInterface
{
    /**
     * @var number
     *
     * @ZOHO\Id
     * @ZOHO\Column(name="user_id")
     */
    protected $id;

    /**
     * @var string
     *
     * @ZOHO\Column(name="email", required="true")
     */
    protected $email;

    /**
     * @var string
     *
     * @ZOHO\Column(name="user_name", required="true")
     */
    protected $name;

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
}
