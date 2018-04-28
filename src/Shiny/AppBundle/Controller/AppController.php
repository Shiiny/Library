<?php

namespace Shiny\AppBundle\Controller;

use Shiny\AppBundle\Entity\Book;
use Shiny\AppBundle\Entity\Category;
use Shiny\AppBundle\Entity\Prof;
use Shiny\AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getRepository(Book::class);
        $books = $em->getBooksComplet();
        return $this->render('@App/public/index.html.twig', array('books' => $books));
    }

    public function listAction($param, $id, $page)
    {
        $entityName = ucfirst($param);
        $nbPerPage = 1;

        $result = $this->getDoctrine()->getRepository('AppBundle:'.$entityName)->find($id);

        $books = $this->getDoctrine()->getRepository(Book::class)->findByParam($param, $result->getId(), $page, $nbPerPage);

        $paging = [
            'page' => $page,
            'nbPages' => ceil(count($books)/$nbPerPage),
            'route' => 'app_list',
            'param' => 'param'
        ];

        if ($param != 'prof') {
            $result = $result->getName();
        }
        else {
            $result = $result->getNameComplet();
        }

        return $this->render('@App/public/list.html.twig', array(
            'books' => $books,
            'paging' => $paging,
            'result' => $result
        ));
    }

    public function singleAction(Book $book)
    {
        if ($book->getAuthor() !== null) {
            $otherBooks = $this->getDoctrine()->getRepository(Book::class)
                ->getFromAuthor($book->getAuthor()->getId());
        }

        return $this->render('@App/public/single.html.twig', array(
            'book' => $book,
            'otherBooks' => $otherBooks
        ));
    }

    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->get('search');
        $author = $request->get('author');
        $category = $request->get('category');


        $nbPerPage = 1;

        $resultSearch = $this->getDoctrine()->getRepository(Book::class)->getSearchWithPaginate($search, $author, $category, $page, $nbPerPage);

        // Récupération des données à n'afficher qu'une fois afin d'éviter les doublons
        $books = $this->getDoctrine()->getRepository(Book::class)->findBySearch($search);
        $categories= [];
        $profsName = [];
        foreach ($books as $book) {
            array_push($categories, $book->getCategory()->getName());
            array_push($profsName, $book->getAuthor()->getNameComplet());
        }

        $paging = [
            'page' => $page,
            'nbPages' => ceil(count($resultSearch)/$nbPerPage),
            'count' => count($resultSearch),
            'route' => 'app_handle_search'
        ];

        return $this->render('@App/public/search.html.twig', array(
            'books' => $resultSearch,
            'paging' => $paging,
            'search' => $search,
            'categories' => array_unique($categories),
            'profsName' => array_unique($profsName)
        ));
    }

    public function contactAction(Request $request)
    {
        $formContact = $this->createForm(ContactType::class);

        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $data = $formContact->getData();
            $this->get('library.mailer')->sendContactMail($data);

            $this->addFlash('info', "Votre message a bien été envoyé");
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('@App/public/contact.html.twig', array('formContact' => $formContact->createView()));
    }

    public function legaleAction()
    {
        return $this->render('@App/public/legale.html.twig');
    }

    public function catalogueAction($page)
    {
        $nbPerPage = 5;
        $allBooks = $this->getDoctrine()->getRepository(Book::class)
            ->findAllBooksWithPaginate($page, $nbPerPage);
        $allProfs = $this->getDoctrine()->getRepository(Prof::class)->findAll();
        $allCategories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $paging = [
            'page' => $page,
            'count' => count($allBooks),
            'nbPages' => ceil(count($allBooks)/$nbPerPage),
            'route' => 'app_catalogue'
        ];

        return $this->render('@App/public/catalogue.html.twig', array(
            'allBooks' => $allBooks,
            'allProfs' => $allProfs,
            'allCategories' => $allCategories,
            'paging' => $paging
        ));
    }
}
