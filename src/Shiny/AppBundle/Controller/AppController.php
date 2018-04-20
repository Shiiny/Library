<?php

namespace Shiny\AppBundle\Controller;

use Shiny\AppBundle\Entity\Book;
use Shiny\AppBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getRepository(Book::class);
        $books = $em->getBooksComplet();
        return $this->render('@App/public/index.html.twig', array('books' => $books));
    }

    public function singleAction(Book $book)
    {
        $em = $this->getDoctrine()->getRepository(Book::class);
        return $this->render('@App/public/single.html.twig', array('book' => $book));
    }

    public function searchBarAction()
    {
        $formSearch = $this->createForm(SearchType::class, null);
        return $this->render('@App/public/form.search.html.twig', array('formSearch' => $formSearch->createView()));
    }

    public function handleSearchAction(Request $request)
    {
        $form = $request->request->get('appbundle_search');
        $search = $form['search'];

        $req = $this->getDoctrine()->getRepository(Book::class)->getSearch($search);
        dump($req);
        return $this->render('@App/public/search.html.twig', array('books' => $req));
    }

}
