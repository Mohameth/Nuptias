<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salle
 *
 * @ORM\Table(name="salle")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\SalleRepository")
 */
class Salle Extends Service
{
    /**
     * @var int
     *
     * @ORM\Column(name="capacite", type="integer", nullable=true)
     */
    private $capacite;


    /**
     * Set capacite
     *
     * @param integer $capacite
     *
     * @return Salle
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return int
     */
    public function getCapacite()
    {
        return $this->capacite;
    }
}
