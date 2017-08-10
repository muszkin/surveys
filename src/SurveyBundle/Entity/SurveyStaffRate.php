<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SurveyStaffRate
 *
 * @ORM\Table(name="survey_staff_rate")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\SurveyStaffRateRepository")
 */
class SurveyStaffRate
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
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey",mappedBy="staff_rate")
     */
    private $survey;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey",mappedBy="resend_staff_rate")
     */
    private $survey_second;


    public function __construct()
    {
        $this->survey = new ArrayCollection();
    }

    /**
     * Add survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return SurveyStaffRate
     */
    public function addSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey[] = $survey;
        $this->survey_second[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     */
    public function removeSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey->removeElement($survey);
    }

    /**
     * Get survey
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Add survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return SurveyStaffRate
     */
    public function addSurveySecond(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey_second[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     */
    public function removeSurveySecond(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey->removeElement($survey);
    }

    /**
     * Get survey
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveySecond()
    {
        return $this->survey_second;
    }

    public function __toString()
    {
        return $this->getName();
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
     * @return SurveyStaffRate
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
}
