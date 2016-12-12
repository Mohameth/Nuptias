<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prestataire
 *
 * @ORM\Table(name="prestataire")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\PrestataireRepository")
 */
class Prestataire extends User
{
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=35, unique=true)
     */
    private $tel;

    public function __construct()
    {
        parent::__construct();
        $this->roles = "Prestataire";
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Prestataire
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

}
