<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Repere
 *
 * @ORM\Table(name="repere")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RepereRepository")
 */
class Repere
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
     * @var int
     *
     * @ORM\Column(name="abcisse", type="integer")
     */
    private $abcisse;

    /**
     * @var int
     *
     * @ORM\Column(name="ordonnee", type="integer")
     */
    private $ordonnee;

    /**
     * @ORM\ManyToOne(targetEntity="Couleur", inversedBy="reperes")
     * @ORM\JoinColumn(name="couleur_id", referencedColumnName="id")
     */
    private $couleur;

    /**
     * @var string
     *
     * @ORM\Column(name="major", type="boolean")
     */
    private $major;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Carte", inversedBy="reperes")
     * @ORM\JoinColumn(name="carte_id", referencedColumnName="id")
     */
    private $carte;
    
    /**
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="reperes")
     * @ORM\JoinColumn(name="images_id", referencedColumnName="id")
     */
    private $image;
    
    /**
   * @ORM\ManyToMany(targetEntity="Texte", cascade={"persist"})
   */
    private $textes;

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
     * Set abcisse
     *
     * @param integer $abcisse
     *
     * @return Repere
     */
    public function setAbcisse($abcisse)
    {
        $this->abcisse = $abcisse;

        return $this;
    }

    /**
     * Get abcisse
     *
     * @return int
     */
    public function getAbcisse()
    {
        return $this->abcisse;
    }

    /**
     * Set ordonnee
     *
     * @param integer $ordonnee
     *
     * @return Repere
     */
    public function setOrdonnee($ordonnee)
    {
        $this->ordonnee = $ordonnee;

        return $this;
    }

    /**
     * Get ordonnee
     *
     * @return int
     */
    public function getOrdonnee()
    {
        return $this->ordonnee;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Repere
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
        $this->textes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->major = false;
    }

    /**
     * Set carte
     *
     * @param \AppBundle\Entity\Carte $carte
     *
     * @return Repere
     */
    public function setCarte(\AppBundle\Entity\Carte $carte = null)
    {
        $this->carte = $carte;

        return $this;
    }

    /**
     * Get carte
     *
     * @return \AppBundle\Entity\Carte
     */
    public function getCarte()
    {
        return $this->carte;
    }

    /**
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Repere
     */
    public function setImage(\AppBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add texte
     *
     * @param \AppBundle\Entity\Texte $texte
     *
     * @return Repere
     */
    public function addTexte(\AppBundle\Entity\Texte $texte)
    {
        $this->textes[] = $texte;

        return $this;
    }

    /**
     * Remove texte
     *
     * @param \AppBundle\Entity\Texte $texte
     */
    public function removeTexte(\AppBundle\Entity\Texte $texte)
    {
        $this->textes->removeElement($texte);
    }

    /**
     * Get textes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTextes()
    {
        return $this->textes;
    }

    /**
     * Set major
     *
     * @param boolean $major
     *
     * @return Repere
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major
     *
     * @return boolean
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set couleur
     *
     * @param \AppBundle\Entity\Couleur $couleur
     *
     * @return Repere
     */
    public function setCouleur(\AppBundle\Entity\Couleur $couleur = null)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return \AppBundle\Entity\Couleur
     */
    public function getCouleur()
    {
        return $this->couleur;
    }
}
