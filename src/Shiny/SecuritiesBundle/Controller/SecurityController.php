<?php

namespace Shiny\SecuritiesBundle\Controller;

use Shiny\SecuritiesBundle\Entity\User;
use Shiny\SecuritiesBundle\Form\ChangePasswordType;
use Shiny\SecuritiesBundle\Form\LoginType;
use Shiny\SecuritiesBundle\Form\ResetType;
use Shiny\SecuritiesBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class, [
           '_username' => $lastUsername
        ]);
        return $this->render('@Securities/login.html.twig', array(
                'form'          => $form->createView(),
                'error'         => $error,
            ));
    }

    public function logoutAction()
    {
        throw new \Exception('Cela ne devrait jamais être atteint !');
    }

    public function registerAction(Request $request)
    {
        $passwordEncoder = $this->get('security.password_encoder');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du plainpassword
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('library.mailer')->sendConfirmationEmail($user);

            $this->addFlash('info', "Un e-mail vous a été envoyé pour le compte ".$user->getEmail());
            return $this->redirectToRoute('security_login');
        }
        return $this->render('@Securities/register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function resetPasswordAction(Request $request)
    {
        $passwordEncoder = $this->get('security.password_encoder');
        $form = $this->createForm(ResetType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère toute la requete 'reset'
            $param = $request->request->all()['reset'];

            // on vérifie que cette email existe dans la db
            $email = $param['email'];
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(array('email' => $email));

            if ($user === null) {
                $this->addFlash('info', "Aucun utilisateur pour cette adresse mail");
                return $this->redirectToRoute('security_register');
            }
            // on crée un nouveau mdp unique que l'on encode et enregistre pour l'utilisateur
            $password = uniqid();
            $passwordCrypt = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($passwordCrypt);
            $em->persist($user);
            $em->flush();
            // on lui renvoi par mail ses identifiants et redirige vers login
            $this->get('library.mailer')->sendResetEmail($user, $password);

            $this->addFlash('info', "Un e-mail vous a été envoyé");
            return $this->redirectToRoute('security_login');
        }

        return $this->render('@Securities/reset.html.twig', array('form' => $form->createView()));
    }


    public function profileAction(Request $request, SessionInterface $session)
    {
        $user = $this->getUser();


        return $this->render('@Securities/profile.html.twig', array(
            'user' => $user
        ));
    }

    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $passwordEncoder = $this->get('security.password_encoder');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère toute la requete 'change_password'
            $data = $request->request->all()['change_password'];

            $newPassword = $data['newPassword'];

            $em = $this->getDoctrine()->getManager();
            // on récupère l'utilsateur actuel
            $user = $this->getUser();

            // on encode le nouveau mdp et on enregistre puis on redirige
            $passwordCrypt = $passwordEncoder->encodePassword($user, $newPassword['first']);
            $user->setPassword($passwordCrypt);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Votre mot de passe a été changé");
            return $this->redirectToRoute('security_profile');
        }

        return $this->render('@Securities/change-password.html.twig', array(
           'form' => $form->createView()
        ));
    }

    public function activateAction(Request $request)
    {
        $token = $request->query->get('token');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array('token' => $token));

        if ($user !== null) {
            // on passe le compte de l'utilisateur en actif et on met le token a null
            $user->setIsActive(true);
            $user->setToken(null);
            $em->persist($user);
            $em->flush();

            // on envoi un message falsh et l'authenticator authentifie l'utisateur en le connectant automatiquement et redirige
            $this->addFlash('success', "Votre compte est désormais activé");
            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user, $request, $this->get('security.login_form_authenticator'), 'main'
                );
        }
        return false;
    }
}
