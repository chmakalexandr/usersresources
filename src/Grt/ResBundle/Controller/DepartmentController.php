<?php

namespace Grt\ResBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Grt\ResBundle\Entity\Department;
use Grt\ResBundle\Form\DepartmentType;


/**
 * Class PageController
 * @package Intex\OrgBundle\Controller
 */
class DepartmentController extends Controller
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

        $departments = $em->getRepository('GrtResBundle:Department')->findAll();
        return $this->render('GrtResBundle:Department:index.html.twig', array(
                'departments' => $departments)
        );
    }

    public function addDepartmentAction()
    {
        $department = new Department();

        $form = $this->createForm(DepartmentType::class, $department);

        return $this->render('GrtResBundle:Department:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function createDepartmentAction(Request $request)
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('User can not be added'));
            return $this->redirect($this->generateUrl('grt_departments'));
        }

        return $this->render('GrtResBundle:Department:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function showUsersAction($departmentId, $page = 1,$field = 'firstname', $order = 'ASC')
    {
        $em = $this->getDoctrine()
            ->getManager();
        $department = $em->getRepository('GrtResBundle:Department')->find($departmentId);
        if (!$department) {
            throw $this->createNotFoundException('Unable to find department.');
        }

        $users = $department->getUsers();

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
