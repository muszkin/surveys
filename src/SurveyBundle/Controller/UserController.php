<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 08.11.16
 * Time: 08:38
 */

namespace SurveyBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SurveyBundle\Entity\User;
use SurveyBundle\Form\ChangePassword;
use SurveyBundle\Form\FilterUserListType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package SurveyBundle\Controller
 * @Route("/users")
 */
class UserController extends Controller
{

    /**
     * @Route("/list",name="showAllUsers")
     */
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');

        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');

        $filters = $this->createForm(FilterUserListType::class);

        $users = $em->getRepository('SurveyBundle:User')->findAll();
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

        foreach ($users as $user){
            $ratings[$user->getId()]['nps'] = $this->get('count')->nps($start,$end,$user);
            $ratings[$user->getId()]['reply'] = $this->get('count')->reply($start,$end,$user);
            $ratings[$user->getId()]['phone_in'] = $this->get('count')->phonein($start,$end,$user);
        }

        return $this->render('@Survey/admin/user/list.html.twig', array(
            'users' => $users,
            'ratings' => $ratings,
            'filters' => $filters->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/new",name="user_new")
     */
    public function createAction(Request $request){
        $user = new User();
        $form = $this->createForm('SurveyBundle\Form\RegistrationType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush($user);

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('@Survey/admin/user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing team entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('SurveyBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $fosUserManager = $this->get('fos_user.user_manager');

            $fosUserManager->updateUser($user,false);

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('@Survey/admin/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a team entity.
     *
     * @param User $user The team entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Deletes a team entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
        }

        return $this->redirectToRoute('showAllUsers');
    }

    /**
     * Show user stats
     * @Route("/{id}", name="show_user")
     */
    public function showAction(Request $request,User $user)
    {
        $user = $this->getDoctrine()->getRepository('SurveyBundle:User')->find($user);

        $filters = [
            'All' => 'all',
            'Opened' => 'open_date',
            'Voted' => 'vote_date',
            'Resend send' => 'resend_date',
            'Resend open' => 'resend_open_date',
            'Resend voted' => 'resend_vote_date',
            'With cancel request' => 'user_comment',
        ];

        $start = new \DateTime('first day of this month 00:00:00');
        $start->format('Y-m-d');
        $end = new \DateTime('last day of this month 23:59:59');
        $end->format('Y-m-d');
        $surveys = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->findAllForUserSurvey($user, $start, $end);

        $form = $this->createForm('SurveyBundle\Form\FilterUserSurveyType',null,['filters'=>$filters]);

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

            $surveys = $this->getDoctrine()->getRepository('SurveyBundle:Survey')->findAllForUserSurveyFilter($user,$data['filters'],$data['type'], $start, $end);

        }

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $surveys,
            $request->query->getInt('page',1),
            10
        );

        $ratings['nps'] = $this->get('count')->nps($start,$end,$user);
        $ratings['reply'] = $this->get('count')->reply($start,$end,$user);
        $ratings['phone_in'] = $this->get('count')->phonein($start,$end,$user);

        return $this->render('@Survey/admin/user/show.html.twig',[
            "user" => $user,
            "pagination" => $pagination,
            "filters" => $form->createView(),
            'ratings' => $ratings
        ]);
    }
}