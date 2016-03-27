<?php
namespace ZohoBooksAL\Entity;

/**
 * Project entity
 *
 * @ZOHO\Service(collectionPath="/projects",
 *               collectionName="projects",
 *               collectionItemName="project")
 */
class Project implements EntityInterface
{
    /**
     * @var string
     *
     * @ZOHO\Id
     * @ZOHO\Column(name="project_id")
     */
    protected $id;

    /**
     * @var string
     *
     * @ZOHO\Column(name="project_name", required="true")
     */
    protected $name;

    /**
     * @var string
     *
     * @ZOHO\Column(name="customer_id", required="true")
     */
    protected $customerId;

    /**
     * @var \SplObjectStorage
     *
     * @ZOHO\OneToMany(targetEntity="ZohoBooksAL\Entity\Project\User", name="users")
     */
    protected $users;

    /**
     * @var string
     *
     * @ZOHO\Column(name="description")
     */
    protected $description;

    /**
     * The way you bill your customer.
     * Allowed Values: fixed_cost_for_project, based_on_project_hours, based_on_staff_hours and based_on_task_hours
     *
     * @var string
     *
     * @ZOHO\Column(name="billing_type", required="true")
     */
    protected $billingType;

    /**
     * @var string
     *
     * @ZOHO\Column(name="rate")
     */
    protected $rate;

    /**
     * @var \DateTime
     *
     * @ZOHO\Column(name="created_time", readonly="true",
     *              type="DateTime", format="EEE MMM dd yyyy HH:mm:ss z")
     */
    protected $created;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->users = new \SplObjectStorage();
    }

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
    public function getBillingType()
    {
        return $this->billingType;
    }

    /**
     * @param string $billingType
     */
    public function setBillingType($billingType)
    {
        $this->billingType = $billingType;
    }

    /**
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param string $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Project\User $price
     */
    public function addUser(Project\User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->attach($user);
        }
    }

    /**
     * @param \SplObjectStorage $users
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
    }
}
