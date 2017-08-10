<?php

namespace SurveyBundle\Controller;

use SurveyBundle\Entity\SurveyStaffRate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Surveystaffrate controller.
 *
 * @Route("staffrate")
 */
class SurveyStaffRateController extends Controller
{
    /**
     * Lists all surveyStaffRate entities.
     *
     * @Route("/", name="staffrate_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $surveyStaffRates = $em->getRepository('SurveyBundle:SurveyStaffRate')->findAll();

        return $this->render('@Survey/admin/surveystaffrate/index.html.twig', array(
            'surveyStaffRates' => $surveyStaffRates,
        ));
    }

    /**
     * Creates a new surveyStaffRate entity.
     *
     * @Route("/new", name="staffrate_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $surveyStaffRate = new Surveystaffrate();
        $form = $this->createForm('SurveyBundle\Form\SurveyStaffRateType', $surveyStaffRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($surveyStaffRate);
            $em->flush($surveyStaffRate);

            return $this->redirectToRoute('staffrate_show', array('id' => $surveyStaffRate->getId()));
        }

        return $this->render('@Survey/admin/surveystaffrate/new.html.twig', array(
            'surveyStaffRate' => $surveyStaffRate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a surveyStaffRate entity.
     *
     * @Route("/{id}", name="staffrate_show")
     * @Method("GET")
     */
    public function showAction(SurveyStaffRate $surveyStaffRate)
    {
        $deleteForm = $this->createDeleteForm($surveyStaffRate);

        return $this->render('@Survey/admin/surveystaffrate/show.html.twig', array(
            'surveyStaffRate' => $surveyStaffRate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing surveyStaffRate entity.
     *
     * @Route("/{id}/edit", name="staffrate_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SurveyStaffRate $surveyStaffRate)
    {
        $deleteForm = $this->createDeleteForm($surveyStaffRate);
        $editForm = $this->createForm('SurveyBundle\Form\SurveyStaffRateType', $surveyStaffRate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('staffrate_edit', array('id' => $surveyStaffRate->getId()));
        }

        return $this->render('@Survey/admin/surveystaffrate/edit.html.twig', array(
            'surveyStaffRate' => $surveyStaffRate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a surveyStaffRate entity.
     *
     * @Route("/{id}", name="staffrate_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SurveyStaffRate $surveyStaffRate)
    {
        $form = $this->createDeleteForm($surveyStaffRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($surveyStaffRate);
            $em->flush($surveyStaffRate);
        }

        return $this->redirectToRoute('staffrate_index');
    }

    /**
     * Creates a form to delete a surveyStaffRate entity.
     *
     * @param SurveyStaffRate $surveyStaffRate The surveyStaffRate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SurveyStaffRate $surveyStaffRate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('staffrate_delete', array('id' => $surveyStaffRate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
