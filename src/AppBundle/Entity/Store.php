<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Store
 *
 * @ORM\Table(name="store")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StoreRepository")
 */
class Store
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="UserStore", mappedBy ="store")
     */
    private $userStores;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userStores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Store
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Store
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Store
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Store
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
     * Set updatedAt
     *
     * @param string $updatedAt
     *
     * @return Store
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add userStores
     *
     * @param \AppBundle\Entity\UserStore $userStores
     *
     * @return Store
     */
    public function addUserStores(\AppBundle\Entity\UserStore $userStores)
    {
        $this->userStores[] = $userStores;

        return $this;
    }

    /**
     * Remove userStores
     *
     * @param \AppBundle\Entity\UserStore $userStores
     */
    public function removeUserStores(\AppBundle\Entity\UserStore $userStores)
    {
        $this->userStores->removeElement($userStores);
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

    /**
     * Add userStore
     *
     * @param \AppBundle\Entity\UserStore $userStore
     *
     * @return Store
     */
    public function addUserStore(\AppBundle\Entity\UserStore $userStore)
    {// TODO: Implement __toString() method.
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

    public function __toString()
    {
        return $this->name;
    }
}
