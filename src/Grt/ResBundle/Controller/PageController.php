<?php

namespace Grt\ResBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PageController
 * @package Intex\OrgBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Render main page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('GrtResBundle:Page:index.html.twig');
    }
}
