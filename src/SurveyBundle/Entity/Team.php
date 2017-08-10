<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\TeamRepository")
 */
class Team
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\User", mappedBy="team")
     */
    private $user_id;


    public function __construct()
    {
        $this->user_id = new ArrayCollection();
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
     * @return Team
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
     * Set module
     *
     * @param string $module
     *
     * @return Team
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }


    /**
     * Add user
     *
     * @param \SurveyBundle\Entity\User $user_id
     *
     * @return Team
     */
    public function addUserId(User $user_id){

        $this->user_id[] = $user_id;

        return $this;
    }

    /**
     * Remove User
     *
     * @param \SurveyBundle\Entity\User $user_id
     *
     */
    public function removeUserId(User $user_id){

        $this->user_id->removeElement($user_id);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUserId(){

        return $this->user_id;
    }

    public function __toString()
    {
        return $this->name;
    }
}
