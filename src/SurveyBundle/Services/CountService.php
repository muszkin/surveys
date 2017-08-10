<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 07.11.16
 * Time: 08:53
 */

namespace SurveyBundle\Services;

use Doctrine\ORM\EntityManager;
use SurveyBundle\Entity\Survey;

/**
 * Class CountService
 * @package SurveyBundle\Services
 */
class CountService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @param $user
     * @return object
     */
    public function nps(\DateTime $start,\DateTime $end,$user){
        $type = $this->em->getRepository('SurveyBundle:SurveyType')->findOneBy([
            'type' => 'nps',
        ]);

        $vote = new \stdClass();
        $vote->total = 0;
        $vote->votes = 0;
        $vote->opened = 0;
        $vote->positive = 0;
        $vote->negative = 0;
        $vote->neutral = 0;
        $vote->rate = 0;
        if (is_array($user)){
            if (!empty($user)) {
                $surveys = $this->em->getRepository('SurveyBundle:Survey')->findAllCurrentTeamSurvey($start, $end, $user, $type)->getResult();
            }else{
                return $vote;
            }
        }else {
            $surveys = $this->em->getRepository('SurveyBundle:Survey')->findAllCurrentUserSurvey($start, $end, $user, $type)->getResult();
        }
        $vote->total = count($surveys);

        foreach ($surveys as $survey){
            /**
             * @var Survey $survey
             */
            if ($survey->getOpenDate() != null){
                $vote->opened += 1;
            }
            if ($survey->getVoteDate() != null){
                if ($survey->getCancel() == null || $survey->getCancel() == false) {
                    $vote->votes += 1;
                    if ($survey->getResendVoteDate() != null) {
                        switch($survey->getResendRate()->getValue()){
                            case 10:
                            case 9:
                                $vote->positive += 1;
                                break;
                            case 8:
                            case 7:
                                $vote->neutral += 1;
                                break;
                            default:
                                $vote->negative += 1;
                                break;
                        }
                    } else {
                        switch($survey->getRate()->getValue()){
                            case 10:
                            case 9:
                                $vote->positive += 1;
                                break;
                            case 8:
                            case 7:
                                $vote->neutral += 1;
                                break;
                            default:
                                $vote->negative += 1;
                                break;
                        }
                    }
                }
            }
        }


        if ($vote->positive > 0) {
            $positive = ($vote->positive * 100) / $vote->votes;
        }else{
            $positive = 0;
        }
        if ($vote->negative > 0) {
            $negative = ($vote->negative * 100) / $vote->votes;
        }else{
            $negative = 0;
        }
        $vote->rate = $positive - $negative;

        return $vote;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @param $user
     * @return object
     */
    public function reply(\DateTime $start,\DateTime $end,$user){
        $type = $this->em->getRepository('SurveyBundle:SurveyType')->findOneBy([
            'type' => 'reply',
        ]);

        $vote = new \stdClass();
        $vote->total = 0;
        $vote->votes = 0;
        $vote->opened = 0;
        $vote->rate = 0;
        if (is_array($user)){
            if (!empty($user)) {
                $surveys = $this->em->getRepository('SurveyBundle:Survey')->findAllCurrentTeamSurvey($start, $end, $user, $type)->getResult();
            }else{
                return $vote;
            }
        }else {
            $surveys = $this->em->getRepository('SurveyBundle:Survey')->findAllCurrentUserSurvey($start, $end, $user, $type)->getResult();
        }
        $vote->total = count($surveys);

        foreach ($surveys as $survey){
            /**
             * @var Survey $survey
             */
            if ($survey->getOpenDate() != null){
                $vote->opened += 1;
            }
            if ($survey->getVoteDate() != null){
                if ($survey->getCancel() == null || $survey->getCancel() == false) {
                    $vote->votes += 1;
                    if ($survey->getResendVoteDate() != null) {
                        $vote->vote += $survey->getResendRate()->getValue();
                    } else {
                        $vote->vote += $survey->getRate()->getValue();
                    }
                }
            }
        }
        if ($vote->vote > 0) {
            $vote->rate = ($vote->vote * 100) / $vote->votes;
        }else{
            $vote->rate = 0;
        }
        return $vote;
    }

    public function phonein(\DateTime $start,\DateTime $end,$user){
        $type = $this->em->getRepository('SurveyBundle:SurveyType')->findOneBy([
            'type' => 'phone_in',
        ]);

        $vote = new \stdClass();
        $vote->total = 0;
        $vote->votes = 0;
        $vote->opened = 0;
        $vote->positive = 0;
        $vote->negative = 0;
        $vote->neutral = 0;
        $vote->rate = 0;
        if (is_array($user)){
            if (!empty($user)) {
                $surveys = $this->em->getRepository('SurveyBundle:Survey')->findAllCurrentTeamSurvey($start, $end, $user, $type)->getResult();
            }else{
                return $vote;
            }
        }else {
            $surveys = $this->em->getRepository('SurveyBundle:Survey')->findAllCurrentUserSurvey($start, $end, $user, $type)->getResult();
        }
        $vote->total = count($surveys);

        foreach ($surveys as $survey){
            /**
             * @var Survey $survey
             */
            if ($survey->getOpenDate() != null){
                $vote->opened += 1;
            }
            if ($survey->getVoteDate() != null){
                if ($survey->getCancel() == null || $survey->getCancel() == false) {
                    $vote->votes += 1;
                    if ($survey->getResendVoteDate() != null) {
                        switch($survey->getResendRate()->getValue()){
                            case 10:
                            case 9:
                                $vote->positive += 1;
                                break;
                            case 8:
                            case 7:
                                $vote->neutral += 1;
                                break;
                            default:
                                $vote->negative += 1;
                                break;
                        }
                    } else {
                        switch($survey->getRate()->getValue()){
                            case 10:
                            case 9:
                                $vote->positive += 1;
                                break;
                            case 8:
                            case 7:
                                $vote->neutral += 1;
                                break;
                            default:
                                $vote->negative += 1;
                                break;
                        }
                    }
                }
            }
        }


        if ($vote->positive > 0) {
            $positive = ($vote->positive * 100) / $vote->votes;
        }else{
            $positive = 0;
        }
        if ($vote->negative > 0) {
            $negative = ($vote->negative * 100) / $vote->votes;
        }else{
            $negative = 0;
        }
        $vote->rate = $positive - $negative;


        return $vote;
    }
}