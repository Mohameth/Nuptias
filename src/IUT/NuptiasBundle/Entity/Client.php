<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 * @UniqueEntity(fields = "username", targetClass = "IUT\NuptiasBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "IUT\NuptiasBundle\Entity\User", message="fos_user.email.already_used")
 */
class Client extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
   * @ORM\ManyToMany(targetEntity="IUT\NuptiasBundle\Entity\Service", cascade={"persist"})
   */
   private $services;



    /**
     * Add service
     *
     * @param \IUT\NuptiasBundle\Entity\Service $service
     *
     * @return Client
     */
    public function addService(\IUT\NuptiasBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \IUT\NuptiasBundle\Entity\Service $service
     */
    public function removeService(\IUT\NuptiasBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }
}
