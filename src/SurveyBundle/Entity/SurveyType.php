<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SurveyType
 *
 * @ORM\Table(name="survey_type")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\SurveyTypeRepository")
 */
class SurveyType
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
     * @ORM\Column(name="type", type="string", length=255, unique=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="count_module", type="string", length=255)
     */
    private $countModule;

    /**
     * @var boolean
     * @ORM\Column(name="extra_question", type="boolean")
     */
    private $extraQuestions;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey",mappedBy="survey_type")
     */
    private $survey;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Rate",mappedBy="survey_type")
     */
    private $rate;

    public function __construct()
    {
        $this->survey = new ArrayCollection();
        $this->rate = new ArrayCollection();
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
     * Set type
     *
     * @param string $type
     *
     * @return SurveyType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set countModule
     *
     * @param string $countModule
     *
     * @return SurveyType
     */
    public function setCountModule($countModule)
    {
        $this->countModule = $countModule;

        return $this;
    }

    /**
     * Get countModule
     *
     * @return string
     */
    public function getCountModule()
    {
        return $this->countModule;
    }

    /**
     * Add surveyId
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return SurveyType
     */
    public function addSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey[] = $survey;

        return $this;
    }

    /**
     * Remove surveyId
     *
     * @param \SurveyBundle\Entity\Survey $survey
     */
    public function removeSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey->removeElement($survey);
    }

    /**
     * Get surveyId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    public function __toString()
    {
        return $this->getType();
    }

    /**
     * Add rate
     *
     * @param \SurveyBundle\Entity\Rate $rate
     *
     * @return SurveyType
     */
    public function addRate(\SurveyBundle\Entity\Rate $rate)
    {
        $this->rate[] = $rate;

        return $this;
    }

    /**
     * Remove rate
     *
     * @param \SurveyBundle\Entity\Rate $rate
     */
    public function removeRate(\SurveyBundle\Entity\Rate $rate)
    {
        $this->rate->removeElement($rate);
    }

    /**
     * Get rate
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @return boolean
     */
    public function isExtraQuestions()
    {
        return $this->extraQuestions;
    }

    /**
     * @param boolean $extraQuestions
     */
    public function setExtraQuestions($extraQuestions)
    {
        $this->extraQuestions = $extraQuestions;
    }

    /**
     * Get extraQuestions
     *
     * @return boolean
     */
    public function getExtraQuestions()
    {
        return $this->extraQuestions;
    }
}
