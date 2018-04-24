<?php

namespace Shiny\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Shiny\AppBundle\Entity\Book;
use Shiny\AppBundle\Entity\Prof;
use Shiny\UploadBundle\Annotation\Uploadable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AdminContentController
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminContentController extends Controller
{

    public function indexAction($param, $page)
    {
        $entityName = ucfirst($param);
        $em = $this->getDoctrine()->getManager();
        $nbPerPage = 5;

        if ($param !== 'book') {
            $results = $em->getRepository('AppBundle:'.$entityName)->findAllWithPaginate($page, $nbPerPage);
        }
        else {
            $results = $em->getRepository('AppBundle:Book')->findAllBooksWithPaginate($page, $nbPerPage);
        }

        $nbPages = ceil(count($results)/$nbPerPage);
        $paging = [
            'page' => $page,
            'nbPages' => $nbPages,
            'route' => 'admin_index',
            'param' => $param
        ];

        if($page > $nbPages) {
            throw $this->createNotFoundException("La Page ".$page." n'existe pas.");
        }

        return $this->render('@Admin/admin/'.$param.'.index.html.twig', array(
            'results' => $results,
            'paging' => $paging
        ));
    }

    public function addAction(Request $request, $param)
    {
        $entityName = 'Shiny\AppBundle\Entity\\'.ucfirst($param);

        $entity = new $entityName();
        $form = $this->createForm('Shiny\AdminBundle\Form\\'.ucfirst($param).'Type', $entity);
        $formCategory = $this->createForm('Shiny\AdminBundle\Form\CategoryType');
        $formProf = $this->createForm('Shiny\AdminBundle\Form\ProfAddType');

        if ($param === "prof") {
            $form = $this->createForm('Shiny\AdminBundle\Form\ProfAddType', $entity);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', "L'élément a bien été ajouté.");
            return $this->redirectToRoute('admin_index', array('param' => $param));
        }
        return $this->render('@Admin/admin/'.$param.'.add.html.twig', array(
            'entity' => $entity,
            'form'  =>  $form->createView(),
            'formProf'  => $formProf->createView(),
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
        $formProf = $this->createForm('Shiny\AdminBundle\Form\ProfAddType');

        if ($param === "prof") {

            $form = $this->createForm('Shiny\AdminBundle\Form\ProfAddType', $entity);
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entity->setUpdatedAt(new \DateTime());

            $em->flush();

            $this->addFlash('info', "L'élément a été modifié.");
            return $this->redirectToRoute('admin_index', array('param' => $param));
        }
        return $this->render('@Admin/admin/'.$param.'.edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'formProf' => $formProf->createView(),
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