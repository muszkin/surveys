<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 03.11.16
 * Time: 15:45
 */

namespace SurveyBundle\Controller;


use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SurveyBundle\Entity\Survey;
use SurveyBundle\Entity\SurveyResend;
use SurveyBundle\Entity\User;
use SurveyBundle\Form\FilterUserListType;
use SurveyBundle\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SurveyController
 * @package SurveyBundle\Controller
 * @Route("/survey")
 */
class SurveyController extends Controller
{
    /**
     * @param Request $request
     * @Route("/add",name="addSurvey")
     * @throws \Exception
     * @return JsonResponse
     */

    public function addSurvey(Request $request){
        $logger = $this->get('logger');
        $data = $request->request->get('survey');

        $data = json_decode($data);


        if ($data == null){
            throw new \Exception("Invalid data");
        }

        if (isset($data->sid)) {
            $user = $this->getDoctrine()->getRepository('SurveyBundle:User')->findOneBy([
                "sid" => $data->sid
            ]);
        }else if (isset($data->admin_id)){
            $user = $this->getDoctrine()->getRepository('SurveyBundle:User')->findOneBy([
                "admin_id" => $data->admin_id
            ]);
        }else{
            throw new \Exception("Can't match user");
        }

        if (is_null($user)){

            $logger->addError(var_export($data,true));
            throw new \Exception("No user in database");
        }

        if (isset($data->type)) {
            $survey_type = $this->getDoctrine()->getRepository('SurveyBundle:SurveyType')->findOneBy([
                "type" => $data->type
            ]);
        }else{
            throw new \Exception("No type specified");
        }
        try {
            /** @var Survey $survey */
            try {
                $survey = $this->get($data->type)->createSurvey($data, $user, $survey_type);
            }catch (\Exception $exception){
                $logger->addDebug("Error when creating survey:\n ".$exception->getMessage());
            }
            if ($survey->getSurveyType()->getId() == 3){
                $mailer = $this->get('survey_mailer')->sendPhoneInSurvey($survey);
                $logger->addDebug("Mail debug:".var_export($mailer,true));
            }
            $return["url"] = $this->get('url')->voteUrl($survey);
        }catch (\Exception $exception){
            $return['error'] = $exception->getMessage();
        }

        return new JsonResponse($return);
    }

    /**
     * @param integer $survey_id
     * @param string $checksum
     * @param Request $request
     * @Route("/vote/{survey_id}/{checksum}",name="survey-vote")
     * @return mixed
     */
    public function voteSurvey($survey_id = null,$checksum = null,Request $request)
    {
        $ip = $request->getClientIps();
        if (!$this->get('ip')->checkIp($ip)){
            $result = $this->get('translator')->trans("You can't vote in work",[],'SurveyBundle');
            return $this->render(
                '@Survey/vote/after.html.twig',
                [
                    'info' =>
                        $result
                ]
            );
        }
        if (!$checksum){
            $checksum = $request->query->get('checksum');
        }

        $survey = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->findOneBy([
            "id" => $survey_id,
            "checksum" => $checksum
        ]);

        if ($survey == null){
            $result = $this->get('translator')->trans("Survey doesn't exists",[],'SurveyBundle');
            return $this->render(
                '@Survey/vote/after.html.twig',
                [
                    'info' =>
                        $result
                ]
            );
        }

        $result = $this->get(
            $survey->getSurveyType()->getType()
        )->validate($survey);

        if (0 == $result || 1 == $result){
            $type = $survey->getSurveyType()->getCountModule();

            if (!$this->get($survey->getSurveyType()->getType())->isChecksum($survey)){
                $result = $this->get('translator')->trans("Wrong checksum for survey",[],'SurveyBundle');
                return $this->render(
                    '@Survey/vote/after.html.twig',
                    [
                        'info' =>
                            $result
                    ]
                );
            }

            $form = $this->createForm('SurveyBundle\Form\VoteType',$survey);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                $survey = $this->get(
                    $type
                )->sendVote($form,$survey);

                if (1 == $result) {
                    $mailer_survey = $this->get('survey_mailer')->sendResendNotifyToStaff($survey);
                }else{
                    $mailer_survey = $this->get('survey_mailer')->sendNotifyToStaff($survey);
                }

                return $this->render(
                    '@Survey/vote/after.html.twig',
                    [
                        'info' => $this->get('translator')->trans('Thank you for voting in this survey',[],'SurveyBundle'),
                    ]
                );
            }
            return $this->render('@Survey/vote/'.$type.'.html.twig',['form'=>$form->createView()]);
        }else{
            switch($result){
                case 5:
                    $result = $this->get('translator')->trans("You can't vote again yet",[],'SurveyBundle');
                    break;
                case 4:
                    $result = $this->get('translator')->trans("You already voted in this survey",[],'SurveyBundle');
                    break;
                case 3:
                    $result = $this->get('translator')->trans("Time for again vote has passed",[],'SurveyBundle');
                    break;
                case 2:
                    $result = $this->get('translator')->trans("Time for vote has passed",[],'SurveyBundle');
                    break;
            }
            return $this->render(
                '@Survey/vote/after.html.twig',
                [
                    'info' =>
                        $result
                ]
            );
        }
    }

    /**
     * @param Survey $survey
     * @Route("/edit/{survey}",name="survey_edit")
     * @return mixed
     */
    public function editAction(Survey $survey,Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $referer = $request->headers->get('referer');
        if ($survey->getUserId()->getId() != $user->getId()){
            $result = $this->get('translator')->trans("This is not your survey",[],'SurveyBundle');
            return $this->render(
                '@Survey/vote/after.html.twig',
                [
                    'info' =>
                        $result
                ]
            );
        }

        return $this->render('@Survey/panel/survey/edit.html.twig',[
            'survey' => $survey,
            'referer' => $referer

        ]);
    }

    /**
     * @return Response
     * @Route("/",name="survey_list")
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');
        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');
        /** @var Query $surveys */
        $surveys = $em->getRepository('SurveyBundle:Survey')->findAllCurrentMonth();


        $filters = [
            $this->get('translator')->trans('All',[],'SurveyBundle') => 'all',
            $this->get('translator')->trans('Opened',[],'SurveyBundle') => 'open_date',
            $this->get('translator')->trans('Voted',[],'SurveyBundle') => 'vote_date',
            $this->get('translator')->trans('Resend send',[],'SurveyBundle') => 'resend_date',
            $this->get('translator')->trans('Resend open',[],'SurveyBundle') => 'resend_open_date',
            $this->get('translator')->trans('Resend voted',[],'SurveyBundle') => 'resend_vote_date',
            $this->get('translator')->trans('With cancel request',[],'SurveyBundle') => 'user_comment',
            $this->get('translator')->trans('With cancel request (need reaction)',[],'SurveyBundle') => 'admin_comment',
        ];

        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');
        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');

        $form = $this->createForm('SurveyBundle\Form\FilterSurveyType',null,['filters'=>$filters]);

        $form->handleRequest($request);
        $limit = 50;
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if ($data['start'] == null){
                $start = new \DateTime('first day of this month 00:00:00');
                $start->format('Y-m-d');
            }else{
                $start = $data['start']->setTime(0,0,0);
            }

            if ($data['end'] == null){
                $end = new \DateTime('last day of this month 23:59:59');
                $end->format('Y-m-d');
            }else{
                $end = $data['end']->setTime(23,59,59);
            }

            $limit = $form->get('limit')->getData();

            $surveys = $em->getRepository('SurveyBundle:Survey')->findAllForUserSurveyFilter(
                $form->get('user')->getData(),
                $form->get('filters')->getData(),
                $form->get('type')->getData(),
                $start,
                $end
            );



        }
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $surveys,
            $request->query->getInt('page',1),
            $limit
        );

        return $this->render('@Survey/admin/survey/index.html.twig',[
            "filters" => $form->createView(),
            "pagination" => $pagination
        ]);
    }

    /**
     * @param $survey
     * @param $react
     * @param Request $request
     * @Route("/react/{survey}/{react}",name="survey_react")
     * @return mixed
     */
    public function react(Survey $survey,$react = null,Request $request){
        $survey = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->find($survey);

        if ($react != null){
            $admin_comment = $request->get('admin_comment');
            $survey->setCancel($react);
            $survey->setAdminComment($admin_comment);
            $em = $this->getDoctrine()->getEntityManager();
            try {
                $em->persist($survey);
                $em->flush($survey);
                return new JsonResponse([
                    "success" => 1,
                ]);
            }catch (ORMException $exception){
                return new JsonResponse([
                    "success" => 0,
                ]);
            }
        }


        return $this->render('@Survey/admin/survey/react.html.twig',[
            "survey" => $survey
        ]);
    }

    /**
     * @param $type
     * @param Request $request
     * @Route("/userSurvey/{type}",name="survey_user")
     * @return mixed
     */
    public function userSurvey($type,Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();


        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');
        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');

        switch($type){
            case 'resend_ready':
            case 'cancel_request':
                $surveys = $this->getDoctrine()->getRepository('SurveyBundle:Survey')
                                ->findAllCurrentUserSurveySpecial($start,$end,$user,$type);
                break;
            default:
                $type = $this->getDoctrine()->getRepository('SurveyBundle:SurveyType')->findOneBy([
                    "type" => $type,
                ]);

                $surveys = $this->getDoctrine()->getRepository('SurveyBundle:Survey')
                    ->findAllCurrentUserSurvey($start,$end,$user,$type);
                break;
        }


        $filters = [
            'All' => 'all',
            'Opened' => 'open_date',
            'Voted' => 'vote_date',
            'Resend send' => 'resend_date',
            'Resend open' => 'resend_open_date',
            'Resend voted' => 'resend_vote_date',
            'User comment' => 'user_comment',
        ];

        $form = $this->createForm('SurveyBundle\Form\FilterSelfUserSurveyType',null,['filters'=>$filters]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if ($data['start'] == null){
                $start = new \DateTime('first day of this month 00:00:00');
                $start->format('Y-m-d');
            }else{
                $start = $data['start']->setTime(0,0,0);
            }

            if ($data['end'] == null){
                $end = new \DateTime('last day of this month 23:59:59');
                $end->format('Y-m-d');
            }else{
                $end = $data['end']->setTime(23,59,59);
            }

            $surveys = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->findAllForUserSurveyFilter(
                $user,
                $form->get('filters')->getData(),
                $type,
                $start,
                $end
            );

        }

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $surveys,
            $request->query->getInt('page',1),
            50
        );

        return $this->render('@Survey/panel/survey/index.html.twig',[
            "pagination" => $pagination,
            "filters" => $form->createView()
        ]);
    }

    /**
     * @param $survey
     * @param Request $request
     * @Route("/cancel/{survey}",name="survey_cancel_request")
     * @return mixed
     */
    public function cancelRequest(Survey $survey,Request $request){
        $survey = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->find($survey);

        if ($request->isMethod('POST')){
            $user_comment = $request->get('user_comment');
            $em = $this->getDoctrine()->getEntityManager();
            try {
                $survey->setUserComment($user_comment);
                $em->persist($survey);
                $em->flush($survey);
                return new JsonResponse([
                    "success" => 1,
                ]);
            }catch (ORMException $exception){
                return new JsonResponse([
                    "success" => 0,
                ]);
            }
        }


        return $this->render('@Survey/panel/survey/cancel.html.twig',[
            "survey" => $survey
        ]);
    }

    /**
     * @param $survey
     * @param Request $request
     * @Route("/resend/{survey}",name="survey_resend_request")
     * @return mixed
     */
    public function resendRequest(Survey $survey,Request $request){
        $survey = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->find($survey);


        if ($request->isMethod('POST')){
            $contact = $request->get('contact');
            $contact_date = $request->get('contact_date');
            $user_comment = $request->get('user_comment');
            $resend_date = new \DateTime('now');
            $em = $this->getDoctrine()->getEntityManager();
            try {
                $survey->setResendDate($resend_date);
                $em->persist($survey);
                $em->flush($survey);

                $surveyResend = new SurveyResend();
                $surveyResend->setResendDate($resend_date);
                $surveyResend->setUserComment($user_comment);
                $surveyResend->setContact($contact);
                $surveyResend->setContactDate(new \DateTime($contact_date));
                $surveyResend->setSurvey($survey);
                $em->persist($surveyResend);
                $em->flush($surveyResend);
                $mailer = $this->get('survey_mailer')->sendResendNotifyToClient($survey);

                return new JsonResponse([
                    "success" => 1,
                    "mailer" => $mailer
                ]);
            }catch (ORMException $exception){
                return new JsonResponse([
                    "success" => 0,
                ]);
            }
        }


        return $this->render('@Survey/panel/survey/resend.html.twig',[
            "survey" => $survey,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/import",name="survey_import")
     * @return JsonResponse
     * @throws \Exception
     */
    public function import(Request $request){
        $data = json_decode($request->getContent());

        if (isset($data->sid)) {
            $user = $this->getDoctrine()->getRepository('SurveyBundle:User')->findOneBy([
                "sid" => $data->sid
            ]);
        }else if (isset($data->admin_id)){
            $user = $this->getDoctrine()->getRepository('SurveyBundle:User')->findOneBy([
                "admin_id" => $data->admin_id
            ]);
        }else{
            throw new \Exception("Can't match user");
        }

        if (isset($data->type)) {
            $survey_type = $this->getDoctrine()->getRepository('SurveyBundle:SurveyType')->findOneBy([
                "type" => $data->type
            ]);
        }else{
            throw new \Exception("No type specified");
        }


        try {
            $service = $this->get($data->type)->importSurvey($data, $user, $survey_type);
            $return["url"] = $this->get('url')->voteUrl($service);
        }catch (\Exception $exception){
            $return['error'] = $exception->getMessage();
        }

        return new JsonResponse($return);
    }

    /**
     * @Route("/cancelList",name="cancel")
     */
    public function cancel(Request $request){
        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');
        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');

        $form = $this->createForm('SurveyBundle\Form\FilterCancelType');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if ($data['start'] == null){
                $start = new \DateTime('first day of this month 00:00:00');
                $start->format('Y-m-d');
            }else{
                $start = $data['start']->setTime(0,0,0);
            }

            if ($data['end'] == null){
                $end = new \DateTime('last day of this month 23:59:59');
                $end->format('Y-m-d');
            }else{
                $end = $data['end']->setTime(23,59,59);
            }

        }

        $surveys = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->findAllCancel($start,$end);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $surveys,
            $request->query->getInt('page',1),
            50
        );

        return $this->render('@Survey/admin/survey/index.html.twig',[
            "pagination" => $pagination,
            "filters" => $form->createView()
        ]);
    }


    /**
     * @Route("/teams_rating",name="teams_rating")
     */
    public function wholeTeamRatings(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');

        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');

        $filters = $this->createForm(FilterUserListType::class);

        $teams = $em->getRepository('SurveyBundle:Team')->findAll();

        $filters->handleRequest($request);

        if ($filters->isSubmitted() && $filters->isValid()){
            $data = $filters->getData();
            if ($data['start'] == null){
                $start = new \DateTime('first day of this month 00:00:00');
                $start->format('Y-m-d');
            }else{
                $start = $data['start']->setTime(0,0,0);
            }

            if ($data['end'] == null){
                $end = new \DateTime('last day of this month 23:59:59');
                $end->format('Y-m-d');
            }else{
                $end = $data['end']->setTime(23,59,59);
            }
        }

        foreach ($teams as $team){
            $users = $em->getRepository('SurveyBundle:User')->findBy(["team" => $team]);
            $ratings[$team->getName()]['nps'] = $this->get('count')->nps($start,$end,$users);
            $ratings[$team->getName()]['reply'] = $this->get('count')->reply($start,$end,$users);
            $ratings[$team->getname()]['phone_in'] = $this->get('count')->phonein($start,$end,$users);
        }

        return $this->render('@Survey/admin/rate.last.month.html.twig', array(
            'ratings' => $ratings,
            'filters' => $filters->createView()
        ));
    }

}