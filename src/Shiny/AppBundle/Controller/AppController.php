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
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avez reçu une nouvelle candidature.'
        );
        $message->addTo('contact@web-shiny.fr')
            ->addFrom('admin@monsite.com');
        $this->get('mailer')->send($message);

        $em = $this->getDoctrine()->getRepository(Book::class);
        $books = $em->getBooksComplet();
        return $this->render('@App/public/index.html.twig', array('books' => $books));
    }

    public function singleAction(Book $book)
    {
        $otherBooks = $this->getDoctrine()->getRepository(Book::class)
            ->getFromAuthor($book->getAuthor()->getNameComplet());

        return $this->render('@App/public/single.html.twig', array(
            'book' => $book,
            'otherBooks' => $otherBooks
        ));
    }

    public function searchBarAction()
    {
        $formSearch = $this->createForm(SearchType::class, null);
        return $this->render('@App/public/form.search.html.twig', array('formSearch' => $formSearch->createView()));
    }

    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->get('search');

        $nbPerPage = 2;
        $countBook = $this->getDoctrine()->getRepository(Book::class)->countBySearch($search);

        $resultSearch = $this->getDoctrine()->getRepository(Book::class)->getSearchWithPaginate($search, $page, $nbPerPage);

        $paging = [
            'page' => $page,
            'nbPages' => ceil($countBook/$nbPerPage),
            'count' => $countBook,
            'route' => 'app_handle_search'
        ];

        return $this->render('@App/public/search.html.twig', array(
            'books' => $resultSearch,
            'paging' => $paging,
            'search' => $search
        ));
    }


    public function contactAction(Request $request)
    {
        $formContact = $this->createForm('Shiny\AppBundle\Form\ContactType');
        return $this->render('@App/public/contact.html.twig', array('formContact' => $formContact->createView()));
    }
}
