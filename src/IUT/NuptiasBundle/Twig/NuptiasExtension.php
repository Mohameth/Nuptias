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

  public function getFunctions()
  {
    return array(
      'class' => new \Twig_SimpleFunction('class', array($this, 'getClass'))
    );
  }

  /**
   * Retourne la classe de l'objet passé en paramètre
   */
  public function getClass($object)
  {
    return (new \ReflectionClass($object))->getShortName();
  }

  /**
   * Retourne true si l'utilisateur est prestataire
   */
  public function estPrestataireFilter($user)
  {
    return (get_class($user) == 'IUT\NuptiasBundle\Entity\Prestataire');
  }

  public function getName()
  {
    return 'iut_nuptias_extension';
  }
}