<?php
namespace ZohoBooksAL\Entity\Project;
use ZohoBooksAL\Entity\EntityInterface;

/**
 * User entity
 */
class User implements EntityInterface
{

    /**
     * @var string
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
     * @var double
     *
     * @ZOHO\Column(name="rate")
     */
    protected $rate;

    /**
     * @var number
     *
     * @ZOHO\Column(name="budget_hours")
     */
    protected $budgetHours;

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

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return number
     */
    public function getBudgetHours()
    {
        return $this->budgetHours;
    }

    /**
     * @param number $budgetHours
     */
    public function setBudgetHours($budgetHours)
    {
        $this->budgetHours = $budgetHours;
    }
}
