<?php

namespace Shiny\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
        return $this->render('@Admin/admin/admin.dashboard.html.twig');
    }
}
