<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="prestataire")
 * @UniqueEntity(fields = "username", targetClass = "IUT\NuptiasBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "IUT\NuptiasBundle\Entity\User", message="fos_user.email.already_used")
 */
class Prestataire
{
<<<<<<< HEAD


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

=======
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
>>>>>>> f665beb44a76f5c6d29c23a98aed3fb5c96b132e

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=35)
     */

    private $tel;

    public function __construct()
    {
        parent::__construct();
        $this->roles = "Prestataire";
    }


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=65)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=255)
     */
    private $rue;

<<<<<<< HEAD
=======
    /**
     * @var int
     *
     * @ORM\Column(name="codePostal", type="integer", length=35, unique=true)
     */
    private $codePostal;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
>>>>>>> f665beb44a76f5c6d29c23a98aed3fb5c96b132e

}
