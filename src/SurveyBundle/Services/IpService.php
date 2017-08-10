<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 04.11.16
 * Time: 13:18
 */

namespace SurveyBundle\Services;


class IpService
{

    const DISALLOWED_IP = [
        '91.223.167.69',
    ];

    public function checkIp($ip){
        if (is_array($ip)) {
            foreach ($ip as $i) {
                if (in_array($i, self::DISALLOWED_IP)) {
                    return false;
                }
            }
        }else{
            if (in_array($ip, self::DISALLOWED_IP)) {
                return false;
            }
        }
        return true;
    }
}