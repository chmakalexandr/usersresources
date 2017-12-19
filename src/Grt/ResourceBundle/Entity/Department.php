<?php

namespace Grt\ResourceBundle\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="departments")
 * @ORM\HasLifecycleCallbacks
 */
class Department
{
    protected $id;

    protected $name;

    protected $active;

    /**
     * Users collection
     * @ORM\OneToMany(targetEntity="User", mappedBy="department", cascade={"all"})
     * @var ArrayCollection
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }


}