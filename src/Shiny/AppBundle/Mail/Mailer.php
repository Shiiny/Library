<?php

namespace Shiny\AppBundle\Mail;


use Shiny\SecuritiesBundle\Entity\User;

class Mailer {
	private $templating;
	private $mailer;

	public function __construct(\Twig_Environment $templating, \Swift_Mailer $mailer) {
		$this->templating = $templating;
		$this->mailer = $mailer;
	}

	public function sendContactMail($data)
	{
		$subject = 'Formulaire de contact';
		$userMail = $data['email'];
		$body = $this->templating->render('@App/Email/email.contact.html.twig', array('data' => $data));

		$message = (new \Swift_Message())
			->setSubject($subject)
			->setFrom($userMail)
			->setTo('contact@web-shiny.fr')
			->setBody($body,'text/html');
		$this->mailer->send($message);
	}

    public function sendConfirmationEmail(User $user)
    {
        $subject = 'Activation de votre compte';
        $userMail = $user->getEmail();
        $body = $this->templating->render('@Securities/Emails/active.html.twig', array('user' => $user));


        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom('a.oury@collegesevigne.fr')
            ->setTo($userMail)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }


    public function sendResetEmail(User $user, $password)
    {
        $subject = 'Réinitialisation de votre compte';
        $userMail = $user->getEmail();
        $body = $this->templating->render('@Securities/Emails/resetmdp.html.twig', array(
            'user' => $user,
            'password' => $password));

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom('sevigne@gmail.com')
            ->setTo($userMail)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}