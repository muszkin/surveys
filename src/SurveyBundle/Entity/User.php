<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 27.10.16
 * Time: 11:26
 */

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package SurveyBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\AttributeOverrides({
 *  @ORM\AttributeOverride(
 *      name="salt",
 *      column=@ORM\Column(name="salt", type="string", nullable=true)
 *      )
 *  })
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
     * @var int
     * @ORM\Column(name="sid",type="integer",unique=true)
     */
    protected $sid;

    /**
     * @var int
     * @ORM\Column(name="admin_id",type="integer",unique=true)
     */
    protected $admin_id;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\Team",inversedBy="user_id",cascade={"persist","remove"})
     * @ORM\JoinColumn(name="team_id",referencedColumnName="id")
     */
    protected $team;

    /**
     * @var string
     * @ORM\Column(name="name",type="string")
     */
    protected $name;

    /**
     * @var int
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey",mappedBy="user_id")
     */
    protected $survey_id;

    /**
     * User constructor.
     */

    public function __construct()
    {
        parent::__construct();
        $this->survey_id = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @param int $sid
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    /**
     * @return mixed
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @param mixed $admin_id
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     *
     * @return mixed
     */
    public function setTeam(\SurveyBundle\Entity\Team $team = null)
    {
        $this->team = $team;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Add surveyId
     *
     * @param \SurveyBundle\Entity\Survey $surveyId
     *
     * @return User
     */
    public function addSurveyId(\SurveyBundle\Entity\Survey $surveyId)
    {
        $this->survey_id[] = $surveyId;

        return $this;
    }

    /**
     * Remove surveyId
     *
     * @param \SurveyBundle\Entity\Survey $surveyId
     */
    public function removeSurveyId(\SurveyBundle\Entity\Survey $surveyId)
    {
        $this->survey_id->removeElement($surveyId);
    }

    /**
     * Get surveyId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveyId()
    {
        return $this->survey_id;
    }
}
