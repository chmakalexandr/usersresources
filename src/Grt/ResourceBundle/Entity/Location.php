<?php

namespace Grt\ResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="locations")
 * @ORM\HasLifecycleCallbacks
 */
class Location
{
    /**
     * Id location
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Name location
     * @Assert\NotBlank()
     * @ORM\Column(type="string",length=100)
     * @var string
     */
    protected $name;

    /**
     * Enable or disable location
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     * @var boolean
     */
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
        $this->active = true;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }



    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}