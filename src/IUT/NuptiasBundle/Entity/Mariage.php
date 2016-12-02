<?php

namespace IUT\NuptiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mariage
 *
 * @ORM\Table(name="mariage")
 * @ORM\Entity(repositoryClass="IUT\NuptiasBundle\Repository\MariageRepository")
 */
class Mariage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Ville", type="string", length=35)
     */
    private $ville;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=true)
     */
    private $date;


    /**
    *@ORM\ManyToMany(targetEntity="IUT\NuptiasBundle\Entity\Invite", cascade={"persist"})
    **/
    private $Invites;


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
     * Set ville
     *
     * @param string $ville
     *
     * @return Mariage
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Mariage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Invites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add invite
     *
     * @param \IUT\NuptiasBundle\Entity\Invite $invite
     *
     * @return Mariage
     */
    public function addInvite(\IUT\NuptiasBundle\Entity\Invite $invite)
    {
        $this->Invites[] = $invite;

        return $this;
    }

    /**
     * Remove invite
     *
     * @param \IUT\NuptiasBundle\Entity\Invite $invite
     */
    public function removeInvite(\IUT\NuptiasBundle\Entity\Invite $invite)
    {
        $this->Invites->removeElement($invite);
    }

    /**
     * Get invites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvites()
    {
        return $this->Invites;
    }
}
