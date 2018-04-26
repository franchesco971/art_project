<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carte
 *
 * @ORM\Table(name="carte")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarteRepository")
 */
class Carte
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
     * @ORM\Column(name="carte_label", type="string", length=255,nullable=true)
     */
    private $carteLabel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="cartes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="Repere", mappedBy="carte")
     */
    private $reperes;
        
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
     * Set carteLabel
     *
     * @param string $carteLabel
     *
     * @return Carte
     */
    public function setCarteLabel($carteLabel)
    {
        $this->carteLabel = $carteLabel;

        return $this;
    }

    /**
     * Get carteLabel
     *
     * @return string
     */
    public function getCarteLabel()
    {
        return $this->carteLabel;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Carte
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reperes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime('NOW');
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Carte
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add repere
     *
     * @param \AppBundle\Entity\Repere $repere
     *
     * @return Carte
     */
    public function addRepere(\AppBundle\Entity\Repere $repere)
    {
        $this->reperes[] = $repere;

        return $this;
    }

    /**
     * Remove repere
     *
     * @param \AppBundle\Entity\Repere $repere
     */
    public function removeRepere(\AppBundle\Entity\Repere $repere)
    {
        $this->reperes->removeElement($repere);
    }

    /**
     * Get reperes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReperes()
    {
        return $this->reperes;
    }
}
