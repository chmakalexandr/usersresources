<?php

/**
 * @ORM\Entity
 * @ORM\Table(name="locations")
 * @ORM\HasLifecycleCallbacks
 */
class Location
{
    protected $id;

    protected $name;

    protected $active;

    /**
     * Users collection
     * @ORM\OneToMany(targetEntity="User", mappedBy="location", cascade={"all"})
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
}