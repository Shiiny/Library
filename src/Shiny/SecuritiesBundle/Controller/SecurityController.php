<?php

namespace Shiny\SecuritiesBundle\Controller;

use Shiny\SecuritiesBundle\Entity\User;
use Shiny\SecuritiesBundle\Form\LoginType;
use Shiny\SecuritiesBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->sendConfirmationEmail($user);

            $this->addFlash('success', "Le compte ".$user->getEmail()." a bien été crée");
            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user, $request, $this->get('security.login_form_authenticator'), 'main'
                );
        }
        return $this->render('@Securities/register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function sendConfirmationEmail(User $user)
    {
        //$token = $user->getEmailConfirmationToken();
        $subject = 'Confirmation de votre compte';
        $email = $user->getEmail();

        $href = "www.mysymfonyproject.com/app_dev.php/confirm?token=test";// . $token;

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('moi@gmail.com')
            ->setTo( $email )
            ->setBody(
                $this->renderView(
                    '@Securities/Emails/confirm.html.twig',
                    array('href' => $href)
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);

        return true;
    }
}
