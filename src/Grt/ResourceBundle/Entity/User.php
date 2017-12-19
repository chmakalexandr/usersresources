<?php

namespace Grt\ResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 *
 * @ORM\Entity(repositoryClass="Grt\ResourceBundle\Entity\Repository\UserRepository")
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
     * User's company
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * @var \Grt\ResourceBundle\Entity\Department
     */
    protected $department;


    /**
     * User's company
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @var \Grt\ResourceBundle\Entity\Location
     */
    protected $location;

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

    /**
     * @param User $user
     */
    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
    }


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
    public function getBithday()
    {
        return $this->bithday;
    }

    /**
     * @param \DateTime $bithday
     */
    public function setBithday($bithday)
    {
        $this->bithday = $bithday;
    }

    /**
     * @return mixed
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * @param int $inn
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
    }

    /**
     * @return mixed
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * @param int $snils
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;
    }


    public function removeResource(\Grt\ResourceBundle\Entity\Resource $resource)
    {
        $this->users->removeElement($resource);
    }


    /**
     * Set company
     * @param \Grt\ResourceBundle\Entity\Company $company
     * @return User
     */


    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
