<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\AdminRepository")
 */
class Admin extends User
{

  public function __construct()
  {
      parent::__construct();
      $this->roles = "Administrateur";
  }
}
