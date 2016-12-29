<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invite
 *
 * @ORM\Table(name="invite")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\InviteRepository")
 */
class Invite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    function __construct() {
      $this->reponse = 'Non envoyé';
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=35)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=64)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=16)
     */
    private $reponse;

    /**
     * @var envoyerInvitation
     *
     * @ORM\Column(name="envoyerInvitation", type="boolean")
     */
    private $envoyerInvitation;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Invite
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Invite
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     *
     * @return Invite
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return string
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set envoyerInvitation
     *
     * @param boolean $envoyerInvitation
     *
     * @return Invite
     */
    public function setEnvoyerInvitation($envoyerInvitation)
    {
        $this->envoyerInvitation = $envoyerInvitation;

        return $this;
    }

    /**
     * Get envoyerInvitation
     *
     * @return boolean
     */
    public function getEnvoyerInvitation()
    {
        return $this->envoyerInvitation;
    }
}
