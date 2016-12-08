<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\ClientRepository")
 */
class Client Extends User
{
  public function __construct()
  {
      parent::__construct();
      $this->roles = "Organisateur";
  }

}
