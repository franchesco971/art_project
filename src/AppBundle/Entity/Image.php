<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="image_label", type="string", length=255)
     * @JMS\Groups({"ajax"})
     */
    private $imageLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255)
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif"}
     * )
     * @JMS\Groups({"ajax"})
     */
    private $imageName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\OneToMany(targetEntity="Repere", mappedBy="image")
     * @JMS\Exclude()
     */
    private $reperes;
    
    /**
     * @var string
     *
     * @ORM\Column(name="isDisabled", type="boolean")
     * @JMS\Groups({"ajax"})
     */
    private $isDisabled;
    
    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;
    
    /**
     * Constructor
     */
    public function __construct(){
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
     * Set imageLabel
     *
     * @param string $imageLabel
     *
     * @return Image
     */
    public function setImageLabel($imageLabel)
    {
        $this->imageLabel = $imageLabel;

        return $this;
    }

    /**
     * Get imageLabel
     *
     * @return string
     */
    public function getImageLabel()
    {
        return $this->imageLabel;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Image
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
     * @return Image
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
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Image
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set isDisabled
     *
     * @param boolean $isDisabled
     *
     * @return Image
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

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
}
