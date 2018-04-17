<?php

namespace Shiny\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Shiny\AdminBundle\Form\UserFormType;
use Shiny\SecuritiesBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminUserController
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminUserController extends Controller
{
    public function indexUserAction()
    {
        $results = $this->getDoctrine()->getRepository(User::class)->findAll();

        $form = $this->createForm(UserFormType::class);
        return $this->render('@Admin/admin/user.index.html.twig', array(
            'results' => $results,
            'form'  => $form->createView()
        ));
    }
}
