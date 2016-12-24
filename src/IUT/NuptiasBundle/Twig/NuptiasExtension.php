<?php

namespace IUT\NuptiasBundle\Twig;

class NuptiasExtension extends \Twig_Extension
{
  public function getFilters()
  {
    return array(
      new \Twig_SimpleFilter('estPrestataire', array($this, 'estPrestataireFilter')),
    );
  }

  /**
   * Retourne true si l'utilisateur est prestataire
   */
  public function estPrestataireFilter($user)
  {
    if (get_class($user) == 'IUT\NuptiasBundle\Entity\Prestataire') return true;

    return false;
  }

  public function getName()
  {
    return 'iut_nuptias_extension';
  }
}