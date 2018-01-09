<?php

namespace Grt\ResBundle\Controller;

use Grt\ResBundle\Form\ResourceType;
use Grt\ResBundle\Entity\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


/**
 * Class BaseController
 * @package Grt\ResBundle\Controller
 */
class ResourceController extends Controller
{
    function deleteResourceAction($userId,$resourceId)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);

            if (!$resource) {
                throw $this->createNotFoundException('No resource found ');
            }

            $em->remove($resource);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('Resource successfully deleted'));
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        }
    }

    function showResourceAction($userId,$resourceId)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);
            $user = $em->getRepository('GrtResBundle:User')->find($userId);
            if (!$resource) {
                throw $this->createNotFoundException('No resource found ');
            }

            return $this->render('GrtResBundle:Resource:show.html.twig',array('user' => $user, 'resource' => $resource));

        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        }
    }
    function editResourceAction($userId,$resourceId)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);
            $user = $resource->getUser();
            $base = $resource->getBase();
            if (!$resource) {
                throw $this->createNotFoundException('No resource found ');
            }

            $formRes = $this->createFormBuilder($resource);

            $fields = explode(",", $base->getFields());
            foreach ($fields as $field){
                if ($field == 'term'){
                    $formRes->add('term', DateType::class, array('label' => 'Срок действия(YYYY-MM-DD)','data' =>$resource->$field,
                        'widget' => 'single_text','format' => 'yyyy-mm-dd','attr'=> array('class'=>'input-group date form-control')));
                } else {
                    $formRes->add($field,TextType::class, array('label' => $field,'data'=> $resource->$field, 'attr'=> array('class'=>'form-control')));
                }
            }

            return $this->render('GrtResBundle:Resource:form.html.twig', array(
                'form' => $formRes->getForm()->createView(),
                'userId' => $userId,
                'baseId' => $base->getId(),
                'resourceId'=>$resource->getId()
            ));


        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        }
    }
}