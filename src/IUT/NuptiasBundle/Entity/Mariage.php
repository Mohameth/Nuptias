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
     * @ORM\ManyToOne(targetEntity="IUT\NuptiasBundle\Entity\Client")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

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
     * @var integer
     *
     * @ORM\Column(name="nbInvites", type="integer")
     */
    private $nbInvites;// Nombre d'invités éstimés lors du formulaire

    /**
     * @var integer
     *
     * @ORM\Column(name="budget", type="integer", nullable=true)
     */
    private $budget;

    /**
     * @var integer
     *
     * @ORM\Column(name="budgetSalle", type="integer", nullable=true)
     */
    private $budgetSalle;

    /**
     * @var integer
     *
     * @ORM\Column(name="budgetTraiteur", type="integer", nullable=true)
     */
    private $budgetTraiteur;

    /**
     * @var integer
     *
     * @ORM\Column(name="budgetPhotographe", type="integer", nullable=true)
     */
    private $budgetPhotographe;

    /**
     * @var integer
     *
     * @ORM\Column(name="budgetDJ", type="integer", nullable=true)
     */
    private $budgetDJ;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
        $this->nbInvites = 50;
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

    /**
     * Set nbInvites
     *
     * @param integer $nbInvites
     *
     * @return Mariage
     */
    public function setNbInvites($nbInvites)
    {
        $this->nbInvites = $nbInvites;

        return $this;
    }

    /**
     * Get nbInvites
     *
     * @return integer
     */
    public function getNbInvites()
    {
        return $this->nbInvites;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Mariage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set budget
     *
     * @param integer $budget
     *
     * @return Mariage
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set budgetSalle
     *
     * @param integer $budgetSalle
     *
     * @return Mariage
     */
    public function setBudgetSalle($budgetSalle)
    {
        $this->budgetSalle = $budgetSalle;

        return $this;
    }

    /**
     * Get budgetSalle
     *
     * @return integer
     */
    public function getBudgetSalle()
    {
        return $this->budgetSalle;
    }

    /**
     * Set budgetTraiteur
     *
     * @param integer $budgetTraiteur
     *
     * @return Mariage
     */
    public function setBudgetTraiteur($budgetTraiteur)
    {
        $this->budgetTraiteur = $budgetTraiteur;

        return $this;
    }

    /**
     * Get budgetTraiteur
     *
     * @return integer
     */
    public function getBudgetTraiteur()
    {
        return $this->budgetTraiteur;
    }

    /**
     * Set budgetPhotographe
     *
     * @param integer $budgetPhotographe
     *
     * @return Mariage
     */
    public function setBudgetPhotographe($budgetPhotographe)
    {
        $this->budgetPhotographe = $budgetPhotographe;

        return $this;
    }

    /**
     * Get budgetPhotographe
     *
     * @return integer
     */
    public function getBudgetPhotographe()
    {
        return $this->budgetPhotographe;
    }

    /**
     * Set budgetDJ
     *
     * @param integer $budgetDJ
     *
     * @return Mariage
     */
    public function setBudgetDJ($budgetDJ)
    {
        $this->budgetDJ = $budgetDJ;

        return $this;
    }

    /**
     * Get budgetDJ
     *
     * @return integer
     */
    public function getBudgetDJ()
    {
        return $this->budgetDJ;
    }

    /**
     * Set client
     *
     * @param \OC\PlatformBundle\Entity\Client $client
     *
     * @return Mariage
     */
    public function setClient(\IUT\NuptiasBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \OC\PlatformBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
