<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 18.11.16
 * Time: 14:29
 */

namespace SurveyBundle\Services;


use SurveyBundle\Entity\Survey;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService
{
    private $mailer;
    private $render;
    private $trans;

    public function __construct(ContainerInterface $container)
    {
        $this->mailer = $container->get('mailer');
        $this->render = $container->get('templating');
        $this->trans = $container->get('translator');

    }

    public function sendNotifyToAdmin(Survey $survey){
        $render = $this->render;
        $trans = $this->trans;
        $message = \Swift_Message::newInstance()
            ->setSubject($trans->trans('New cancel request',[],'SurveyBundle'))
            ->setFrom($survey->getUserId()->getEmail())
            ->setTo('dariusz.brzezinski@dreamcommerce.com')
            ->setBody($render->render('@Survey/mails/cancel.twig',[
                "survey" => $survey,
            ]),'text/html');

        return $this->mailer->send($message);
    }

    public function sendNotifyToStaff(Survey $survey){
        $render = $this->render;
        $trans = $this->trans;
        $message = \Swift_Message::newInstance()
            ->setSubject($trans->trans('New rate in system',[],'SurveyBundle'))
            ->setFrom($survey->getUserId()->getEmail())
            ->setTo($survey->getUserId()->getEmail())
            ->setBcc('dariusz.brzezinski@dreamcommerce.com')
            ->setBody($render->render('@Survey/mails/newrate.twig',[
                "survey" => $survey,
            ]),'text/html');

        return $this->mailer->send($message);
    }

    public function sendResendNotifyToStaff(Survey $survey){
        $render = $this->render;
        $trans = $this->trans;
        $message = \Swift_Message::newInstance()
            ->setSubject($trans->trans('New rate in system (resend)',[],'SurveyBundle'))
            ->setFrom($survey->getUserId()->getEmail())
            ->setTo($survey->getUserId()->getEmail())
            ->setBcc('dariusz.brzezinski@dreamcommerce.com')
            ->setBody($render->render('@Survey/mails/newrate_resend.twig',[
                "survey" => $survey,
            ]),'text/html');

        return $this->mailer->send($message);
    }

    public function sendResendNotifyToClient(Survey $survey){
        $render = $this->render;
        $trans = $this->trans;
        $message = \Swift_Message::newInstance()
            ->setSubject($trans->trans('Request for re-evaluation',[],'SurveyBundle'))
            ->setFrom($survey->getUserId()->getEmail())
            ->setTo($survey->getClientEmail())
            ->setBcc('dariusz.brzezinski@dreamcommerce.com')
            ->setBody($render->render('@Survey/mails/resend.twig',[
                "survey" => $survey,
            ]),'text/html');

        return $this->mailer->send($message);
    }

    public function sendPhoneInSurvey(Survey $survey){
        $render = $this->render;
        $trans = $this->trans;
        $message = \Swift_Message::newInstance()
            ->setSubject($trans->trans('Survey after phone call',[],'SurveyBundle'))
            ->setFrom(["bok@shoper.pl"=>"Ankiety Shoper"])
            ->setTo($survey->getClientEmail())
            ->setBody($render->render('@Survey/mails/phonein.twig',[
                "survey" => $survey,
            ]),'text/html');

        return $this->mailer->send($message);
    }

    public function sendTestMail($to){
        $message = \Swift_Message::newInstance()
            ->setSubject("test")
            ->setFrom(["bok@shoper.pl"=>"Ankiety Shoper"])
            ->setTo($to)
            ->setBody("Testowa wiadomośc",'text/html');
        return $this->mailer->send($message);
    }
}