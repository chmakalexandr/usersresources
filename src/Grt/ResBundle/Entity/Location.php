<?php

namespace Grt\ResBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="string",length=100,unique=true)
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

    function __toString()
    {
        return $this->name;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Remove user
     *
     * @param \Grt\ResBundle\Entity\User $user
     */
    public function removeUser(\Grt\ResBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }
}
