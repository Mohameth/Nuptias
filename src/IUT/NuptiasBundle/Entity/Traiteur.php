<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Traiteur
 *
 * @ORM\Table(name="traiteur")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\TraiteurRepository")
 */
class Traiteur Extends Service
{
  /**
   * @ORM\ManyToOne(targetEntity="IUT\NuptiasBundle\Entity\Prestataire")
   * @ORM\JoinColumn(nullable=false)
   */
  protected  $prestataire;

  /**
   * Set prestataire
   *
   * @param \IUT\NuptiasBundle\Entity\Prestataire $prestataire
   *
   * @return Service
   */
  public function setPrestataire(\IUT\NuptiasBundle\Entity\Prestataire $prestataire)
  {
    $this->prestataire = $prestataire;

    return $this;
  }

  /**
   * Get prestataire
   *
   * @return \IUT\NuptiasBundle\Prestataire
   */
  public function getPrestataire()
  {
    return $this->prestataire;
  }
}
