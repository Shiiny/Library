<?php

namespace Shiny\SecuritiesBundle\Email;


class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail($to, $from, $subject, $body)
    {
        $mail = new \Swift_Message();

        $mail
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($mail);
    }

    public function sendActivationMail($userEmail)
    {
        $to = $userEmail;
        $from = 'anthony.oury94@gmail.com';
        $subject = 'Votre compte';
        $body = "Bienvenue sur la bibliothèque numérique du Collège Sévigné.";

        $this->sendMail($to, $from, $subject, $body);
    }
}