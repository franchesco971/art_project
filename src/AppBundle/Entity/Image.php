<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="image_label", type="string", length=255)
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
     */
    private $imageName;
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="uploaded_images", fileNameProperty="imageName")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\OneToMany(targetEntity="Repere", mappedBy="image")
     */
    private $reperes;
    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $image
     *
     * @return Product
     */
//    public function setImageFile(File $image = null)
//    {
//        $this->imageFile = $image;
////        dump($image);
//        if ($this->imageFile instanceof UploadedFile) {
//            // It is required that at least one field changes if you are using doctrine
//            // otherwise the event listeners won't be called and the file is lost
//            $this->createdAt = new \DateTimeImmutable();
//        }
//        
//        return $this;
//    }
    
    /**
     * @return File|UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
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
     * Constructor
     */
    public function __construct()
    {
        $this->reperes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime('NOW');
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
}
