<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
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
     * @ORM\OneToMany(targetEntity="UserStore", mappedBy ="user")
     */
    private $userStores;
    
    public function __construct()
    {
        parent::__construct();
        $this->userStores = new ArrayCollection();
    }


    /**
     * Add userStore
     *
     * @param \AppBundle\Entity\UserStore $userStore
     *
     * @return User
     */
    public function addUserStore(\AppBundle\Entity\UserStore $userStore)
    {
        $this->userStores[] = $userStore;

        return $this;
    }

    /**
     * Remove userStore
     *
     * @param \AppBundle\Entity\UserStore $userStore
     */
    public function removeUserStore(\AppBundle\Entity\UserStore $userStore)
    {
        $this->userStores->removeElement($userStore);
    }

    /**
     * Get userStores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserStores()
    {
        return $this->userStores;
    }
}
