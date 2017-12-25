<?php

namespace Grt\ResBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 *
 * @ORM\Entity(repositoryClass="Grt\ResBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * Id user
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * First Name user
     * @Assert\NotBlank()
     * @ORM\Column(type="string",length=100)
     * @var string
     */
    protected $firstname;

    /**
     * Last Name user
     * @Assert\NotBlank()
     * @ORM\Column(type="string",length=100)
     * @var string
     */
    protected $lastname;

    /**
     * Middle Name user
     * @Assert\NotBlank()
     * @ORM\Column(type="string",length=100)
     * @var string
     */
    protected $middlename;

    /**
     * Domain Name user
     * @Assert\NotBlank()
     * @ORM\Column(type="string",length=100,unique=true)
     * @var string
     */
    protected $domainname;

    /**
     * User's department
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * @var \Grt\ResBundle\Entity\Department
     */
    protected $department;

    /**
     * User's location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @var \Grt\ResBundle\Entity\Location
     */
    protected $location;

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * @param string $middlename
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;
    }

    /**
     * @return mixed
     */
    public function getDomainname()
    {
        return $this->domainname;
    }

    /**
     * @param mixed $domainname
     */
    public function setDomainname($domainname)
    {
        $this->domainname = $domainname;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param Department $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }


    /**
     * Users collection
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="user", cascade={"all"})
     * @var ArrayCollection
     */
    protected $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param User $user
     */
    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
    }






    public function removeResource(\Grt\ResBundle\Entity\Resource $resource)
    {
        $this->users->removeElement($resource);
    }


    /**
     * Set company
     * @param \Grt\ResBundle\Entity\Company $company
     * @return User
     */


    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
