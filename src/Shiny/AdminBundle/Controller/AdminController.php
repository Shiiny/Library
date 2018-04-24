<?php

namespace Shiny\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Shiny\AppBundle\Entity\Book;
use Shiny\AppBundle\Entity\Prof;
use Shiny\SecuritiesBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    public function homeAction()
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->getLastBooks(4);
        $users = $this->getDoctrine()->getRepository(User::class)->getLastRegisters(3);
        $profs = $this->getDoctrine()->getRepository(Prof::class)->getLastProfs(5);

        return $this->render('@Admin/admin/admin.dashboard.html.twig', array(
            'books' => $books,
            'users' => $users,
            'profs' => $profs
            ));
    }
}
