<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Couleur
 *
 * @ORM\Table(name="couleur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CouleurRepository")
 */
class Couleur
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
     * @ORM\Column(name="major", type="string", length=255, nullable=true)
     */
    private $major;

    /**
     * @var string
     *
     * @ORM\Column(name="proxy", type="string", length=255, nullable=true)
     */
    private $proxy;
    
    /**
     * @ORM\OneToMany(targetEntity="Repere", mappedBy="couleur")
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
     * Set major
     *
     * @param string $major
     *
     * @return Couleur
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major
     *
     * @return string
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set proxy
     *
     * @param string $proxy
     *
     * @return Couleur
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;

        return $this;
    }

    /**
     * Get proxy
     *
     * @return string
     */
    public function getProxy()
    {
        return $this->proxy;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reperes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add repere
     *
     * @param \AppBundle\Entity\Repere $repere
     *
     * @return Couleur
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
