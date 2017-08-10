<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 04.11.16
 * Time: 10:43
 */

namespace SurveyBundle\Services;


use Doctrine\ORM\EntityManager;
use SurveyBundle\Entity\Rate;
use SurveyBundle\Entity\Survey;
use SurveyBundle\Entity\SurveyStaffRate;
use SurveyBundle\Entity\SurveyType;
use SurveyBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;

class ReplyService extends ValidateAbstractService
{

    protected $em;

    const VOTE = 0;
    const VOTE_RESEND = 1;
    const TOO_OLD = 2;
    const TOO_OLD_RESEND = 3;
    const ALREADY_VOTED = 4;
    const NO_RESEND = 5;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function createSurvey($data,User $user,SurveyType $surveyType){
        $survey = new Survey();

        $survey->setUserId($user);
        $survey->setSurveyTypeId($surveyType);
        $survey->setChecksum($data->checksum);
        $survey->setSendDate(new \DateTime($data->send_date));
        $survey->setClientEmail($data->client_email);
        $survey->setTicketId($data->ticket_id);
        $survey->setPostId($data->post_id);

        try {
            $this->em->persist($survey);
            $this->em->flush();
        }catch(\Exception $exception){
            return $exception->getMessage();
        }

        return $survey;
    }

    public function validate(Survey $survey){

        $check = $this->votingPossible($survey);

        $this->setOpen($check,$survey);

        return $check;
    }

    private function votingPossible(Survey $survey){
        $current_time = new \DateTime('now');

        if ($survey->getVoteDate() != null || $survey->getRate() != null){
            if ($survey->getResendDate() != null){
                if ($survey->getResendDate()->diff($current_time)->days > 3){
                    return self::TOO_OLD_RESEND;
                }else{
                    if ($survey->getResendVoteDate() != null || $survey->getResendRate() != null){
                        return self::ALREADY_VOTED;
                    }else {
                        return self::VOTE_RESEND;
                    }
                }
            }else{
                return self::NO_RESEND;
            }
        }else{
            if ($survey->getSendDate()->diff($current_time)->days > 3){
                return self::TOO_OLD;
            }else{
                return self::VOTE;
            }
        }
    }

    private function setOpen($vote,Survey $survey){
        if ($vote){
            if ($survey->getResendOpenDate() == null && $survey->getResendDate() != null){
                $survey->setResendOpenDate(new \DateTime('now'));
                $this->em->persist($survey);
                $this->em->flush($survey);
            }
        }else{
            if ($survey->getOpenDate() == null){
                $survey->setOpenDate(new \DateTime('now'));
                $this->em->persist($survey);
                $this->em->flush($survey);
            }
        }
    }

    public function sendVote(Form $form,Survey $survey){
        if ($survey->getResendDate() != null){
            $survey->setResendVoteDate(new \DateTime('now'));
            $survey->setResendComment($form->get('comment')->getData());
            $survey->setResendRate($form->getData()->getRate());
            $this->em->persist($survey);
            $this->em->flush($survey);
        }else{
            $survey->setVoteDate(new \DateTime('now'));
            $survey->setComment($form->get('comment')->getData());
            $survey->setRate($form->getData()->getRate());
            $this->em->persist($survey);
            $this->em->flush($survey);
        }
        return $survey;
    }


    public function importSurvey($data,User $user,SurveyType $surveyType){
        $survey = new Survey();

        $survey->setUserId($user);
        $survey->setSurveyTypeId($surveyType);
        $survey->setChecksum($data->checksum);
        $survey->setSendDate(new \DateTime($data->send_date));
        $survey->setClientEmail($data->client_email);
        $survey->setTicketId($data->ticket_id);
        $survey->setPostId($data->post_id);
        $survey->setComment($data->comment);
        $survey->setUserComment($data->user_comment);
        $survey->setCancel($data->cancel);
        $survey->setAdminComment($data->admin_comment);

        if ($data->rate != null){
            $rate = $this->em->getRepository('SurveyBundle:Rate')->findOneBy([
                'survey_type' => $surveyType,
                'value' => $data->rate,
            ]);
        }else{
            $rate = null;
        }
        if ($data->resend_rate != null){
            $resend_rate = $this->em->getRepository('SurveyBundle:Rate')->findOneBy([
                'survey_type' => $surveyType,
                'value' => $data->rate,
            ]);
        }else{
            $resend_rate = null;
        }

        if ($rate){
            $survey->setRate($rate);
            $survey->setOpenDate(new \DateTime(date('Y-m-d H:i:s',$data->vote_date)));
            $survey->setVoteDate(new \DateTime(date('Y-m-d H:i:s',$data->vote_date)));
        }
        if ($resend_rate){
            $survey->setResendRate($resend_rate);
            $survey->setResendDate(new \DateTime(date('Y-m-d H:i:s',$data->resend_date)));
            $survey->setResendOpenDate(new \DateTime(date('Y-m-d H:i:s',$data->resend_date)));
            $survey->setResendVoteDate(new \DateTime(date('Y-m-d H:i:s',$data->resend_vote_date)));
        }


        try {
            $this->em->persist($survey);
            $this->em->flush();
        }catch(\Exception $exception){
            throw new Exception($exception->getMessage());
        }

        return $survey->getId().'/'.$survey->getChecksum();
    }
}