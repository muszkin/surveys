<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rate
 *
 * @ORM\Table(name="rate")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\RateRepository")
 */
class Rate
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
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;


    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\SurveyType",inversedBy="rate")
     * @ORM\JoinColumn(name="survey_type",referencedColumnName="id")
     */
    private $survey_type;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey",mappedBy="rate")
     */
    private $survey;

    /**
     ** @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey",mappedBy="resend_rate")
     */
    private $survey_resend;

    public function __construct()
    {
        $this->survey = new ArrayCollection();
        $this->survey_resend = new ArrayCollection();
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
     * @return Rate
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
     * Set value
     *
     * @param integer $value
     *
     * @return Rate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set surveyType
     *
     * @param \SurveyBundle\Entity\SurveyType $surveyType
     *
     * @return Rate
     */
    public function setSurveyType(\SurveyBundle\Entity\SurveyType $surveyType = null)
    {
        $this->survey_type = $surveyType;

        return $this;
    }

    /**
     * Get surveyType
     *
     * @return \SurveyBundle\Entity\SurveyType
     */
    public function getSurveyType()
    {
        return $this->survey_type;
    }

    /**
     * Add survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return Rate
     */
    public function addSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->survey[] = $survey;

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
     * Add surveyResend
     *
     * @param \SurveyBundle\Entity\Survey $surveyResend
     *
     * @return Rate
     */
    public function addSurveyResend(\SurveyBundle\Entity\Survey $surveyResend)
    {
        $this->survey_resend[] = $surveyResend;

        return $this;
    }

    /**
     * Remove surveyResend
     *
     * @param \SurveyBundle\Entity\Survey $surveyResend
     */
    public function removeSurveyResend(\SurveyBundle\Entity\Survey $surveyResend)
    {
        $this->survey_resend->removeElement($surveyResend);
    }

    /**
     * Get surveyResend
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveyResend()
    {
        return $this->survey_resend;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
