<?php

namespace Shiny\AdminBundle\Controller;

use Shiny\AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{
    public function homeAction()
    {
        return $this->render('@Admin/admin/admin.dashboard.html.twig');
    }
}
