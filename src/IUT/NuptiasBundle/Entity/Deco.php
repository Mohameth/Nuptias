<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deco
 *
 * @ORM\Table(name="deco")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\DecoRepository")
 */
class Deco Extends Service
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
