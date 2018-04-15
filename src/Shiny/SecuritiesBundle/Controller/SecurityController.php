<?php

namespace Shiny\SecuritiesBundle\Controller;

use Shiny\SecuritiesBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
           '_username' => $lastUsername
        ]);
        return $this->render('@Securities/login.html.twig', array(
                'form'          => $form->createView(),
                'error'         => $error,
            ));
    }

    public function logoutAction()
    {
        throw new \Exception('This should not be reached');
    }
}
