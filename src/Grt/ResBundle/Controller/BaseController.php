<?php

namespace Grt\ResBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Grt\ResBundle\Form\BaseType;
use Symfony\Component\HttpFoundation\Request;
use Grt\ResBundle\Entity\Base;

/**
 * Class BaseController
 * @package Intex\OrgBundle\Controller
 */
class BaseController extends Controller
{
    const LIMIT_PER_PAGE = 5;
    /**
     * Render list all companies
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBasesAction($page = 1, $field = 'name', $order = 'ASC')
    {
        $em = $this->getDoctrine()->getManager();
        $bases = $em->getRepository('GrtResBundle:Base')->getAllBases($field, $order, $page, self::LIMIT_PER_PAGE);


        $maxPages = ceil($bases->count() / self::LIMIT_PER_PAGE);
        $thisPage = $page;

        return $this->render('GrtResBundle:Base:index.html.twig', array(
            'bases' => $bases,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    /**
     * Render information about company by id
     * @param int $companyId Id organization's
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBaseAction($baseId)
    {
        $em = $this->getDoctrine()->getManager();
        $base = $em->getRepository('GrtResBundle:Base')->find($baseId);

        if (!$base) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $this->render('GrtResBundle:Base:show.html.twig', array(
            'base' => $base
        ));
    }

    /**
     * Renders form for add Base
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newBaseAction()
    {
        $base = new Base();

        $form = $this->createForm(BaseType::class, $base);

        return $this->render('GrtResBundle:Base:form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * Add/Edit Base in DB
     * @param Request $request
     * @param int $baseId organization's Id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createBaseAction(Request $request, $baseId = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($baseId) {
            $base = $em->getRepository('GrtResBundle:Base')->find($baseId);
        } else {
            $base = new Base();
        }

        $form = $this->createForm(BaseType::class, $base);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($base);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('Base was be added!'));
            return $this->redirect($this->generateUrl('grt_list_bases'));
        }

        $this->addFlash('error', $this->get('translator')->trans('Base can not be added'));
        return $this->render('GrtResBundle:Base:form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function editBaseAction($baseId)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $base = $em->getRepository('GrtResBundle:Base')->find($baseId);

        if (!$base) {
            throw $this->createNotFoundException('Unable to find base.');
        }

        $form = $this->createForm(BaseType::class, $base);

        return $this->render('GrtResBundle:Base:form.html.twig', array(
            'form' => $form->createView(),
            'baseId' => $baseId
        ));

    }
}
