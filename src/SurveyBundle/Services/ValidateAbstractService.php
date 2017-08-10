<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 22.11.16
 * Time: 09:33
 */

namespace SurveyBundle\Services;


use SurveyBundle\Entity\Survey;

abstract class ValidateAbstractService
{

    const SALT = 'WygielJeWeWiosce';

    public function isChecksum(Survey $survey){

        if ($survey->getClientId() != null){
            $md5 = md5($survey->getClientId() . $survey->getClientEmail() . self::SALT);
        }else {
            $md5 = md5($survey->getTicketId() . $survey->getPostId() . self::SALT);
        }

        if ($md5 == $survey->getChecksum()){
            return true;
        }
        return false;
    }
}