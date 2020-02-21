<?php

namespace App\services;

class MailService
{

    private $mailer;
    private $mail;

    public function __construct(\Swift_Mailer $mailer, $mail)
    {
        $this->mailer = $mailer;
        $this->mail = $mail;

    }

/*
 * Send email for active account
 */
    public function sendEmail($email, $objet, $content)
    {

        $message = (new \Swift_Message($objet))
            ->setFrom($this->mail)
            ->setTo($email)
            ->setBody(
                $content,
                'text/html'
            );

        $this->mailer->send($message);
    }
}
