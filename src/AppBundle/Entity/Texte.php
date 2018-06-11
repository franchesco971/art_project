<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Texte
 *
 * @ORM\Table(name="texte")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TexteRepository")
 */
class Texte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"ajax"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="texte_label", type="string", length=255)
     * @JMS\Groups({"ajax"})
     */
    private $texteLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @JMS\Groups({"ajax"})
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255, nullable=true)
     * @JMS\Groups({"ajax"})
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="livre", type="string", length=255, nullable=true)
     * @JMS\Groups({"ajax"})
     */
    private $livre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\OneToMany(targetEntity="Repere", mappedBy="carte")
     * @JMS\Exclude()
     */
    private $reperes;
    
    /**
     * @var string
     *
     * @ORM\Column(name="isDisabled", type="boolean")
     */
    private $isDisabled;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reperes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime('NOW');
        $this->isDisabled = false;
    }
    
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
     * Set texteLabel
     *
     * @param string $texteLabel
     *
     * @return Texte
     */
    public function setTexteLabel($texteLabel)
    {
        $this->texteLabel = $texteLabel;

        return $this;
    }

    /**
     * Get texteLabel
     *
     * @return string
     */
    public function getTexteLabel()
    {
        return $this->texteLabel;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Texte
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Texte
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set livre
     *
     * @param string $livre
     *
     * @return Texte
     */
    public function setLivre($livre)
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get livre
     *
     * @return string
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Texte
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
     * Add repere
     *
     * @param \AppBundle\Entity\Repere $repere
     *
     * @return Texte
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

    /**
     * Set isDisabled
     *
     * @param boolean $isDisabled
     *
     * @return Texte
     */
    public function setIsDisabled($isDisabled)
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    /**
     * Get isDisabled
     *
     * @return boolean
     */
    public function getIsDisabled()
    {
        return $this->isDisabled;
    }
}
