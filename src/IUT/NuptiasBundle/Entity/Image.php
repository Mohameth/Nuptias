<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="URL", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", nullable=true)
     */
    private $description;

    /**
    * @ORM\ManyToOne(targetEntity="IUT\NuptiasBundle\Entity\Service")
    * @ORM\JoinColumn(nullable=false)
    **/
    private $Service;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uRL
     *
     * @param string $uRL
     *
     * @return Image
     */
    public function setURL($uRL)
    {
        $this->uRL = $uRL;

        return $this;
    }

    /**
     * Get uRL
     *
     * @return string
     */
    public function getURL()
    {
        return $this->uRL;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set service
     *
     * @param \IUT\NuptiasBundle\Entity\Service $service
     *
     * @return Image
     */
    public function setService(\IUT\NuptiasBundle\Entity\Service $service)
    {
        $this->Service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \IUT\NuptiasBundle\Entity\Service
     */
    public function getService()
    {
        return $this->Service;
    }
}
