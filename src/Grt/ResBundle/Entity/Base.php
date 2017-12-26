<?php

namespace Grt\ResBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity(repositoryClass="Grt\ResBundle\Entity\Repository\BaseRepository")
 * @ORM\Table(name="bases")
 */
class Base
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
     * Fields base's
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @var string
     */
    protected $fields;


    /**
     * Enable or disable location
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $active;

    /**
     * Users collection
     * @ORM\OneToMany(targetEntity="Grt\ResBundle\Entity\Resource", mappedBy="base", cascade={"all"})
     * @var ArrayCollection
     */
    protected $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->active = true;
    }

    /**
     * @param User $user
     */
    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
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
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @return string
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
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
     * Remove resource
     *
     * @param \Grt\ResBundle\Entity\Resource $resource
     */
    public function removeResource(\Grt\ResBundle\Entity\Resource $resource)
    {
        $this->resources->removeElement($resource);
    }
}
