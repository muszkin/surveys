<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 04.11.16
 * Time: 10:44
 */

namespace SurveyBundle\Services;


use Doctrine\ORM\EntityManager;
use SurveyBundle\Entity\Survey;
use SurveyBundle\Entity\SurveyType;
use SurveyBundle\Entity\User;

class PhoneInService extends ValidateAbstractService
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
        $survey->setClientId($data->client_id);

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

        if ($survey->getVoteDate() != null || $survey->getResendRate() != null){
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
            if ($survey->getResendOpenDate() == null){
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

}