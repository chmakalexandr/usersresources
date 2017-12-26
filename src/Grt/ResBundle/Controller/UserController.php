<?php

namespace Grt\ResBundle\Controller;

use Grt\ResBundle\Entity\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Grt\ResBundle\Entity\User;
use Grt\ResBundle\Entity\Base;
use Grt\ResBundle\Form\UserType;
use Grt\ResBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
/**
 * Class UserController
 * @package Intex\OrgBundle\Controller
 */
class UserController extends Controller
{
    const LIMIT_PER_PAGE = 5;
    /**
     * Render list all users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsersAction($page = 1, $field = 'firstname', $order = 'ASC')
    {
        $em = $this->getDoctrine()
            ->getManager();

        $users = $em->getRepository('GrtResBundle:User')
            ->getAllUsers($field, $order, $page, self::LIMIT_PER_PAGE);

        $maxPages = ceil($users->count() / self::LIMIT_PER_PAGE);
        $thisPage = $page;
        // Pass through the 3 above variables to calculate pages in twig

        return $this->render('GrtResBundle:User:index.html.twig', array(
            'users' => $users,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));

    }

    public function editUsersAction($userId)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $user = $em->getRepository('GrtResBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        $form = $this->createForm(UserType::class, $user);

        return $this->render('GrtResBundle:User:form.html.twig', array(
            'form' => $form->createView(),
            'userId' => $userId
        ));

    }

    /**
     * Render information about user by id
     * @param int $userId Id user's
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GrtResBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        //$bases = $em->getRepository('GrtResBundle:Base')->findAll();

        $form = $this->createFormBuilder()
            ->add('base', EntityType::class, array(
                'class'      => 'Grt\ResBundle\Entity\Base',
                'choice_label' => 'name'

             ))->getForm()->createView();

        return $this->render('GrtResBundle:User:show.html.twig', array(
            'user' => $user,
            'form' => $form
        ));
    }

    /**
     * Renders list users of the company
     * @param int $companyId Id organization's
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listOrgUsersAction($companyId, $page = 1, $field = 'firstname', $order = 'ASC')
    {
        $company = $this->getCompany($companyId);
        $users = $company->getUsers();

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        $usersSort = $users->toArray();
        $this->sortArrayByKey($usersSort, $field, $order);

        $maxPages = ceil($users->count() / self::LIMIT_PER_PAGE);
        $thisPage = $page;

        return $this->render('GrtResBundle:User:users.html.twig', array(
            'company' => $company,
            'users' =>   $usersSort,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    /**
     * Renders form for add user to company
     * @param int $companyId organization's Id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newUserAction()
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        return $this->render('GrtResBundle:User:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Add user in DB
     * @param Request $request
     * @param int $userId organization's Id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request, $userId = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($userId) {
            $user = $em->getRepository('GrtResBundle:User')->find($userId);
        } else {
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('User was be added!'));
            return $this->redirect($this->generateUrl('grt_users'));
        }

        $this->addFlash('error', $this->get('translator')->trans('User can not be added'));
        return $this->render('GrtResBundle:User:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Load companies with users from XML file
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadUsersAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $file = $request->files->get('form');
            $companies = $this->get('app.xmlfile_deserialize')->deserializeCompanies($file);
            $existingCompanies = $em->getRepository('Intex\OrgBundle\Entity\Company')->getExistingCompanies($companies);
            $existingOgrns = $em->getRepository('Intex\OrgBundle\Entity\Company')->getOgrns($existingCompanies);

            foreach ($companies as $organization) {
                $company = $this->getNewOrganizationWithUsers($organization, $existingCompanies, $existingOgrns);

                if ($company) {
                    $em->persist($company);
                }
            }

            $em->flush();
        } catch (Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('Unnable add users in Db. Check XML file'));
            return $this->redirect($this->generateUrl('grt_user_upload'));
        }

        $this->addFlash('success', $this->get('translator')->trans('Users successfully loaded'));

        return $this->redirect($this->generateUrl('grt_user_upload'));
    }


    /**
     * Add resource to user
     * @param Request $request
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addResourceUserAction(Request $request, $userId)
    {

        $form = $request->get("form");
        $baseId = $form['base'];
        $base = $this->getBaseById(intval($form['base']));
        $formRes = $this->createFormBuilder();

            $fields = explode(",", $base->getFields());
            foreach ($fields as $field){
                $formRes->add($field,TextType::class, array('label' => $field,'attr'=> array('class'=>'form-control')));
            }


        return $this->render('GrtResBundle:Resource:form.html.twig', array(
            'form' => $formRes->getForm()->createView()
        ));
    }

    public function createUserResourceAction(Request $request, $userId)
    {

    }


    /**
     * Renders form for upload users from XML file
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadXmlAction()
    {
        $form = $this->createFormBuilder()
            ->add('file', FileType::class, array('label' => $this->get('translator')->trans('Load XML file'),
                "attr" => array("accept" => ".xml",)))
            ->getForm();

        return $this->render('GrtResBundle:User:upload.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Shows the company in which the user belongs
     * @param int $companyId Id organization's
     * @return \Grt\ResBundle\Entity\Company|null|object
     */
    protected function getUserById($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $user;
    }


    /**
     * Shows the company in which the user belongs
     * @param int $companyId Id organization's
     * @return \Grt\ResBundle\Entity\Company|null|object
     */
    protected function getBaseById($baseId)
    {
        $em = $this->getDoctrine()->getManager();
        $base = $em->getRepository('Grt\ResBundle\Entity\Base')->find($baseId);

        if (!$base) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $base;
    }


    /**
     * Return company from array $companies in which the Primary State Registration Number = $ogrn
     * @param int $ogrn Primary State Registration Number organization's
     * @param array $companies array organizations
     * @return \Grt\ResBundle\Entity\Company|null|object
     */
    protected function getCompanyByOgrn($ogrn, $companies)
    {
        foreach ($companies as $company) {
            if ($company->getOgrn() == $ogrn) {
                return $company;
            }
        }

        return null;
    }

    protected function getNewOrganizationWithUsers($organization, $existingCompanies, $existingOgrns)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $organization->getUsers();
        $newUsers = $em->getRepository('Intex\OrgBundle\Entity\User')->getNewUsers($users);

        $ogrn = $organization->getOgrn();
        if (in_array($ogrn, $existingOgrns)) {
            $organization = $this->getCompanyByOgrn($ogrn, $existingCompanies);
        }

        if (!empty($newUsers)) {
            foreach ($newUsers as $user) {
                $user->setCompany($organization);
                $organization->addUser($user);
            }

            if (!in_array($organization->getOgrn(), $existingOgrns)) {
                return $organization;
            }
        }

        return null;
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
