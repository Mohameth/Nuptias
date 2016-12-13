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
class Prestataire extends User
{

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=35)
     */

    private $tel;

    /**
     *@var string
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

}
