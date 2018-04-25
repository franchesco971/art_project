<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Carte", mappedBy="user")
     */
    private $cartes;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add carte
     *
     * @param \AppBundle\Entity\Carte $carte
     *
     * @return User
     */
    public function addCarte(\AppBundle\Entity\Carte $carte)
    {
        $this->cartes[] = $carte;

        return $this;
    }

    /**
     * Remove carte
     *
     * @param \AppBundle\Entity\Carte $carte
     */
    public function removeCarte(\AppBundle\Entity\Carte $carte)
    {
        $this->cartes->removeElement($carte);
    }

    /**
     * Get cartes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartes()
    {
        return $this->cartes;
    }
}
