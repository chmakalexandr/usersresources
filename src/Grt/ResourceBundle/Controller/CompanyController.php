<?php

namespace Grt\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CompanyController
 * @package Intex\OrgBundle\Controller
 */
class CompanyController extends Controller
{
    const LIMIT_PER_PAGE = 5;
    /**
     * Render list all companies
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCompaniesAction($page = 1, $field = 'name', $order = 'ASC')
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('GrtResourceBundle:Company')->getAllCompanies($field, $order, $page, self::LIMIT_PER_PAGE);


        $maxPages = ceil($companies->count() / self::LIMIT_PER_PAGE);
        $thisPage = $page;

        return $this->render('GrtResourceBundle:Company:index.html.twig', array(
            'companies' => $companies,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    /**
     * Render information about company by id
     * @param int $companyId Id organization's
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCompanyAction($companyId)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('GrtResourceBundle:Company')->find($companyId);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $this->render('GrtResourceBundle:Company:show.html.twig', array(
            'company' => $company,
        ));
    }
}
