<?php

namespace Shiny\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Shiny\AdminBundle\Form\UserFormType;
use Shiny\SecuritiesBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminUserController
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class AdminUserController extends Controller
{
    public function indexUserAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $formRole = $this->createForm(UserFormType::class);
        return $this->render('@Admin/admin/user.index.html.twig', array(
            'users' => $users,
            'formRole'  => $formRole->createView()
        ));
    }

    public function roleUserAction(Request $request, User $user)
    {
        if ($request->isMethod('POST')) {
            $newRole = [$request->request->get('new_role_user')];
            $user->setRoles($newRole);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "Le role de l'utilisateur a été modifié.");
        }
        return $this->redirectToRoute('admin_user_index');
    }


    public function deleteUserAction(Request $request, User $user)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('warning', "L'utilisateur ".$user->getUsername()." a été supprimé.");
        }
        return $this->redirectToRoute('admin_user_index');
    }
}
