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
    const LIMIT_PER_PAGE = 5;
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

    public function showUsersAction($locationId, $page = 1,$field = 'firstname', $order = 'ASC')
    {
        $em = $this->getDoctrine()
            ->getManager();
        $location = $em->getRepository('GrtResBundle:Location')->find($locationId);
        if (!$location) {
            throw $this->createNotFoundException('Unable to find base.');
        }

        $users = $location->getUsers();

        $usersSort = $users->toArray();
        $this->sortArrayByKey($usersSort, $field, $order);

        $maxPages = ceil($users->count() / self::LIMIT_PER_PAGE);
        $thisPage = $page;

        return $this->render('GrtResBundle:User:index.html.twig', array(
            'users' =>   $usersSort,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    private function sortArrayByKey(&$array,$key,$order){
        usort($array,function ($a, $b) use(&$key,&$order)
        {
            if($order == 'ASC') {
                return (strtolower($a->{$key}) < strtolower($b->{$key})) ? -1 : 1;
            } else {
                return (strtolower($a->{$key}) > strtolower($b->{$key})) ? -1 : 1;
            }
            if(strcmp(strtolower($a->{$key}), strtolower($b->{$key}))){ return 0;}
        });

    }


}
