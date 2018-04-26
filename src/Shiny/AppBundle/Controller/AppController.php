<?php

namespace Shiny\AppBundle\Controller;

use Shiny\AppBundle\Entity\Book;
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

    public function listAction(Request $request, $page)
    {
        $search = $request->query->get('search');

        $nbPerPage = 5;
        $result = $this->getDoctrine()->getRepository(Book::class)->getSearchWithPaginate($search, $page, $nbPerPage);

        $paging = [
            'page' => $page,
            'nbPages' => ceil(count($result)/$nbPerPage),
            'route' => 'app_list'
        ];

        return $this->render('@App/public/list.html.twig', array(
            'books' => $result,
            'search' => $search,
            'paging' => $paging
        ));
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

    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->get('search');

        $nbPerPage = 5;
        $countBook = $this->getDoctrine()->getRepository(Book::class)->countBySearch($search);

        $resultSearch = $this->getDoctrine()->getRepository(Book::class)->getSearchWithPaginate($search, $page, $nbPerPage);

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
            'nbPages' => ceil($countBook/$nbPerPage),
            'count' => $countBook,
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
            $this->sendContactMail($data);

            $this->addFlash('info', "Votre message a bien été envoyé");
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('@App/public/contact.html.twig', array('formContact' => $formContact->createView()));
    }

    private function sendContactMail($data)
    {
        $subject = 'Formulaire de contact';
        $userMail = $data['email'];

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($userMail)
            ->setTo('contact@web-shiny.fr')
            ->setBody(
                $this->renderView('@App/Email/email.contact.html.twig', array('data' => $data)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
