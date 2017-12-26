<?php

namespace Grt\ResBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="departments")
 * @ORM\HasLifecycleCallbacks
 */
class Department
{
    /**
     * Id department
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Name department
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
     * @ORM\OneToMany(targetEntity="User", mappedBy="department", cascade={"all"})
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

    function __toString()
    {
        return $this->name;
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
