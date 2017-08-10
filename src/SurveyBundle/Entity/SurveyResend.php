<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SurveyResend
 *
 * @ORM\Table(name="survey_resend")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\SurveyResendRepository")
 */
class SurveyResend
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
     * @var \DateTime
     *
     * @ORM\Column(name="resend_date", type="datetime")
     */
    private $resendDate;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contact_date", type="datetime")
     */
    private $contactDate;

    /**
     * @var string
     *
     * @ORM\Column(name="user_comment", type="string", length=255)
     */
    private $userComment;

    /**
     *
     * @ORM\OneToOne(targetEntity="SurveyBundle\Entity\Survey",inversedBy="resend")
     * @ORM\JoinColumn(name="survey_id",referencedColumnName="id")
     */
    private $survey;


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
     * Set resendDate
     *
     * @param \DateTime $resendDate
     *
     * @return SurveyResend
     */
    public function setResendDate($resendDate)
    {
        $this->resendDate = $resendDate;

        return $this;
    }

    /**
     * Get resendDate
     *
     * @return \DateTime
     */
    public function getResendDate()
    {
        return $this->resendDate;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return SurveyResend
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set contactDate
     *
     * @param \DateTime $contactDate
     *
     * @return SurveyResend
     */
    public function setContactDate($contactDate)
    {
        $this->contactDate = $contactDate;

        return $this;
    }

    /**
     * Get contactDate
     *
     * @return \DateTime
     */
    public function getContactDate()
    {
        return $this->contactDate;
    }

    /**
     * Set userComment
     *
     * @param string $userComment
     *
     * @return SurveyResend
     */
    public function setUserComment($userComment)
    {
        $this->userComment = $userComment;

        return $this;
    }

    /**
     * Get userComment
     *
     * @return string
     */
    public function getUserComment()
    {
        return $this->userComment;
    }

    /**
     * Set survey
     *
     * @param string $survey
     *
     * @return SurveyResend
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return string
     */
    public function getSurvey()
    {
        return $this->survey;
    }
}
