<?php

namespace IUT\NuptiasBundle\Twig;

class NuptiasExtension extends \Twig_Extension
{
  public function getFilters()
  {
    return array(
      new \Twig_SimpleFilter('estPrestataire', array($this, 'estPrestataireFilter')),
      new \Twig_SimpleFilter('nbInvitationEnvoye', array($this, 'nbInvitationEnvoyeFilter')),
      new \Twig_SimpleFilter('nbInvitationEnAttente', array($this, 'nbInvitationEnAttenteFilter')),
      new \Twig_SimpleFilter('nbInvitationPositive', array($this, 'nbInvitationPositiveFilter')),
      new \Twig_SimpleFilter('nbInvitationNegative', array($this, 'nbInvitationNegativeFilter')),
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

  public function nbInvitationEnvoyeFilter($invites) {
    $nb = 0;

    foreach ($invites as $invite) {
      if ($invite->getReponse() != 'Non envoyé') $nb++;
    }
    return $nb;
  }

  public function nbInvitationEnAttenteFilter($invites) {
    $nb = 0;

    foreach ($invites as $invite) {
      if ($invite->getReponse() == 'En attente') $nb++;
    }
    return $nb;
  }

  public function nbInvitationPositiveFilter($invites) {
    $nb = 0;

    foreach ($invites as $invite) {
      if ($invite->getReponse() == 'Positive') $nb++;
    }
    return $nb;
  }

  public function nbInvitationNegativeFilter($invites) {
    $nb = 0;

    foreach ($invites as $invite) {
      if ($invite->getReponse() == 'Négative') $nb++;
    }
    return $nb;
  }

  public function getName()
  {
    return 'iut_nuptias_extension';
  }
}