<?php

namespace Shiny\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AdminContentController
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminContentController extends Controller
{

    public function indexAction($param)
    {
        $entityName = ucfirst($param);

        $em = $this->getDoctrine()->getManager();

        if ($param !== 'book') {
            $results = $em->getRepository('AppBundle:'.$entityName)->findAll();
        }
        else {
            $results = $em->getRepository('AppBundle:Book')->getBooksComplet();
        }

        return $this->render('@Admin/admin/'.$param.'.index.html.twig', array(
            'results' => $results,
        ));
    }

    public function addAction(Request $request, $param)
    {
        $entityName = 'Shiny\AppBundle\Entity\\'.ucfirst($param);

        $entity = new $entityName();
        $form = $this->createForm('Shiny\AdminBundle\Form\\'.ucfirst($param).'Type', $entity);
        $formCategory = $this->createForm('Shiny\AdminBundle\Form\CategoryType');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', "L'élément a bien été ajouté.");
            return $this->redirectToRoute('admin_index', array('param' => $param));
        }
        return $this->render('@Admin/admin/'.$param.'.add.html.twig', array(
            'form'  =>  $form->createView(),
            'formCategory' => $formCategory->createView()
        ));

    }

    public function editAction(Request $request, $id, $param)
    {
        $entityName = ucfirst($param);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:'.$entityName)->find($id);

        $form = $this->createForm('Shiny\AdminBundle\Form\\'.$entityName.'Type', $entity);
        $formCategory = $this->createForm('Shiny\AdminBundle\Form\CategoryType');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('info', "L'élément a été modifié.");
            return $this->redirectToRoute('admin_index', array('param' => $param));
        }
        return $this->render('@Admin/admin/'.$param.'.edit.html.twig', array(
            'form' => $form->createView(),
            'formCategory' => $formCategory->createView()
        ));

    }

    public function deleteAction(Request $request, $id, $param)
    {
        $entityName = ucfirst($param);
        $req = $this->getDoctrine()->getRepository('AppBundle:'.$entityName)->find($id);
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($req);
            $em->flush();
        }
        $this->addFlash('danger', "L'élément a bien été supprimé.");
        return $this->redirectToRoute('admin_index', array('param' => $param));
    }


}