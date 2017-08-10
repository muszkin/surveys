<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 27.10.16
 * Time: 10:53
 */

namespace SurveyBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SurveyBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{

    /**
     * @return Response
     * @Route("/",name="index")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(){
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render('@Survey/admin/index.html.twig');
        }else if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $start = new \DateTime('first day of this month 00:00:00');
            $start->format('Y-m-d');
            $end = new \DateTime('last day of this month 23:59:59');
            $end->format('Y-m-d');

            $nps = $this->get('count')->nps($start,$end,$user);
            $reply = $this->get('count')->reply($start,$end,$user);
            $phonein = $this->get('count')->phonein($start,$end,$user);

            return $this->render('@Survey/panel/rating.html.twig',[
                "period" => ["start" => $start,"end" => $end],
                "user" => $user,
                "nps" => $nps,
                "reply" => $reply,
                "phonein" => $phonein,
            ]);
        }
        return $this->render('@Survey/base.html.twig');
    }
}