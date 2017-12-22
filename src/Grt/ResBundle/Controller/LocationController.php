<?php

namespace Grt\ResBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Grt\ResBundle\Entity\Location;
use Grt\ResBundle\Form\LocationType;


/**
 * Class PageController
 * @package Intex\OrgBundle\Controller
 */
class LocationController extends Controller
{
    /**
     * Render main page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $locations = $em->getRepository('GrtResBundle:Location')->findAll();
        return $this->render('GrtResBundle:Location:index.html.twig', array(
            'locations' => $locations)
        );
    }

    public function addLocationAction()
    {
        $locale = new Location();

        $form = $this->createForm(LocationType::class, $locale);

        return $this->render('GrtResBundle:Location:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function createLocationAction(Request $request)
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('User can not be added'));
            return $this->redirect($this->generateUrl('grt_locations'));
        }

        return $this->render('GrtResBundle:Location:form.html.twig', array(
            'form' => $form->createView()
        ));
    }


}
