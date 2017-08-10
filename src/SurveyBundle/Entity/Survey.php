<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\SurveyRepository")
 */
class Survey
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
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\User",inversedBy="survey_id",cascade={"persist"})
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_date",type="datetime")
     */
    private $send_date;

    /**
     * @var \DateTime
     * @ORM\Column(name="open_date",type="datetime",nullable=true)
     */
    private $open_date;

    /**
     * @var \DateTime
     * @ORM\Column(name="vote_date",type="datetime",nullable=true)
     */
    private $vote_date;

    /**
     * @var \DateTime
     * @ORM\Column(name="resend_date",type="datetime",nullable=true)
     */
    private $resend_date;

    /**
     * @var \DateTime
     * @ORM\Column(name="resend_open_date",type="datetime",nullable=true)
     */
    private $resend_open_date;

    /**
     * @var \DateTime
     * @ORM\Column(name="resend_vote_date",type="datetime",nullable=true)
     */
    private $resend_vote_date;

    /**
     * @var int
     * @ORM\Column(name="ticket_id",type="integer",length=11,nullable=true)
     */
    private $ticket_id;

    /**
     * @var int
     * @ORM\Column(name="post_id",type="integer",length=11,nullable=true)
     */
    private $post_id;

    /**
     * @var int
     * @ORM\Column(name="client_id",type="integer",length=11,nullable=true)
     */
    private $client_id;

    /**
     * @var string
     * @ORM\Column(name="client_email",type="string")
     */
    private $client_email;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\SurveyType",inversedBy="survey",cascade={"persist"})
     * @ORM\JoinColumn(name="survey_type",referencedColumnName="id")
     */
    private $survey_type;

    /**
     * @var string
     * @ORM\Column(name="checksum",type="string")
     */
    private $checksum;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\Rate",inversedBy="survey")
     * @ORM\JoinColumn(name="rate",referencedColumnName="id",columnDefinition="INT NULL")
     */
    private $rate;

    /**
     * @var SurveyStaffRate
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\SurveyStaffRate",inversedBy="survey")
     * @ORM\JoinColumn(name="staff_rate",referencedColumnName="id",columnDefinition="INT NULL")
     */
    private $staff_rate;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\Rate",inversedBy="survey_resend")
     * @ORM\JoinColumn(name="resend_rate",referencedColumnName="id",columnDefinition="INT NULL")
     */
    private $resend_rate;

    /**
     * @var SurveyStaffRate
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\SurveyStaffRate",inversedBy="survey_second")
     * @ORM\JoinColumn(name="resend_staff_rate",referencedColumnName="id",columnDefinition=" INT NULL")
     */
    private $resend_staff_rate;

    /**
     * @var string
     * @ORM\Column(name="comment",type="text",nullable=true)
     */
    private $comment;

    /**
     * @var string
     * @ORM\Column(name="resend_comment",type="text",nullable=true)
     */
    private $resend_comment;

    /**
     * @var string
     * @ORM\Column(name="user_comment",type="text",nullable=true)
     */
    private $user_comment;

    /**
     * @var boolean
     * @ORM\Column(name="cancel",type="boolean",nullable=true)
     */
    private $cancel;

    /**
     * @var string
     * @ORM\Column(name="admin_comment",type="text",nullable=true)
     */
    private $admin_comment;

    /**
     * @ORM\OneToOne(targetEntity="SurveyBundle\Entity\SurveyResend",mappedBy="survey")
     */
    private $resend;

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
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param User $user_id
     *
     * @return mixed
     */
    public function setUserId(\SurveyBundle\Entity\User $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->send_date;
    }

    /**
     * @param \DateTime $send_date
     */
    public function setSendDate($send_date)
    {
        $this->send_date = $send_date;
    }

    /**
     * @return \DateTime
     */
    public function getOpenDate()
    {
        return $this->open_date;
    }

    /**
     * @param \DateTime $open_date
     */
    public function setOpenDate($open_date)
    {
        $this->open_date = $open_date;
    }

    /**
     * @return \DateTime
     */
    public function getVoteDate()
    {
        return $this->vote_date;
    }

    /**
     * @param \DateTime $vote_date
     */
    public function setVoteDate($vote_date)
    {
        $this->vote_date = $vote_date;
    }

    /**
     * @return \DateTime
     */
    public function getResendDate()
    {
        return $this->resend_date;
    }

    /**
     * @param \DateTime $resend_date
     */
    public function setResendDate($resend_date)
    {
        $this->resend_date = $resend_date;
    }

    /**
     * @return \DateTime
     */
    public function getResendOpenDate()
    {
        return $this->resend_open_date;
    }

    /**
     * @param \DateTime $resend_open_date
     */
    public function setResendOpenDate($resend_open_date)
    {
        $this->resend_open_date = $resend_open_date;
    }

    /**
     * @return \DateTime
     */
    public function getResendVoteDate()
    {
        return $this->resend_vote_date;
    }

    /**
     * @param \DateTime $resend_vote_date
     */
    public function setResendVoteDate($resend_vote_date)
    {
        $this->resend_vote_date = $resend_vote_date;
    }

    /**
     * @return int
     */
    public function getTicketId()
    {
        return $this->ticket_id;
    }

    /**
     * @param int $ticket_id
     */
    public function setTicketId($ticket_id)
    {
        $this->ticket_id = $ticket_id;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return string
     */
    public function getClientEmail()
    {
        return $this->client_email;
    }

    /**
     * @param string $client_email
     */
    public function setClientEmail($client_email)
    {
        $this->client_email = $client_email;
    }



    /**
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @param string $checksum
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

    /**
     * @return Rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param Rate
     * @return Survey
     */
    public function setRate(Rate $rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * @return SurveyStaffRate
     */
    public function getStaffRate()
    {
        return $this->staff_rate;
    }

    /**
     * @param SurveyStaffRate $staff_rate
     * @return Survey
     */
    public function setStaffRate(SurveyStaffRate $staff_rate)
    {
        $this->staff_rate = $staff_rate;

        return $this;
    }

    /**
     * @return Rate
     */
    public function getResendRate()
    {
        return $this->resend_rate;
    }

    /**
     * @param Rate $resend_rate
     * @return Survey
     */
    public function setResendRate(Rate $resend_rate)
    {
        $this->resend_rate = $resend_rate;

        return $this;
    }

    /**
     * @return SurveyStaffRate
     */
    public function getResendStaffRate()
    {
        return $this->resend_staff_rate;
    }

    /**
     * @param SurveyStaffRate $resend_staff_rate
     * @return Survey
     */
    public function setResendStaffRate(SurveyStaffRate $resend_staff_rate)
    {
        $this->resend_staff_rate = $resend_staff_rate;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getResendComment()
    {
        return $this->resend_comment;
    }

    /**
     * @param string $resend_comment
     */
    public function setResendComment($resend_comment)
    {
        $this->resend_comment = $resend_comment;
    }

    /**
     * @return string
     */
    public function getUserComment()
    {
        return $this->user_comment;
    }

    /**
     * @param string $user_comment
     */
    public function setUserComment($user_comment)
    {
        $this->user_comment = $user_comment;
    }

    /**
     * @return boolean
     */
    public function isCancel()
    {
        return $this->cancel;
    }

    /**
     * @param boolean $cancel
     */
    public function setCancel($cancel)
    {
        $this->cancel = $cancel;
    }

    /**
     * @return string
     */
    public function getAdminComment()
    {
        return $this->admin_comment;
    }

    /**
     * @param string $admin_comment
     */
    public function setAdminComment($admin_comment)
    {
        $this->admin_comment = $admin_comment;
    }


    /**
     * Get cancel
     *
     * @return boolean
     */
    public function getCancel()
    {
        return $this->cancel;
    }

    /**
     * Set surveyType
     *
     * @param \SurveyBundle\Entity\SurveyType $surveyType
     *
     * @return Survey
     */
    public function setSurveyTypeId(\SurveyBundle\Entity\SurveyType $surveyType = null)
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
     * Set surveyType
     *
     * @param \SurveyBundle\Entity\SurveyType $surveyType
     *
     * @return Survey
     */
    public function setSurveyType(\SurveyBundle\Entity\SurveyType $surveyType = null)
    {
        $this->survey_type = $surveyType;

        return $this;
    }

    /**
     * Set resend
     *
     * @param \SurveyBundle\Entity\SurveyResend $resend
     *
     * @return Survey
     */
    public function setResend(\SurveyBundle\Entity\SurveyResend $resend = null)
    {
        $this->resend = $resend;

        return $this;
    }

    /**
     * Get resend
     *
     * @return \SurveyBundle\Entity\SurveyResend
     */
    public function getResend()
    {
        return $this->resend;
    }
}
