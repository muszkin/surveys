<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 04.11.16
 * Time: 12:46
 */

namespace SurveyBundle\Services;


use SurveyBundle\Entity\Survey;

class UrlService
{
    const MAIN_URL = "https://surveys.dashboarddc.com/survey/vote/";

    public function voteUrl(Survey $survey){

        $url = self::MAIN_URL.$survey->getId().'/'.$survey->getChecksum();

        return $url;
    }
}