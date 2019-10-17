<?php

namespace App\services;

class MailService
{

   private $mailer;

   public function __construct(\Swift_Mailer $mailer)
   {
      $this->mailer = $mailer;
   }

   public function sendEmail($email, $objet, $content)
   {

      $message = (new \Swift_Message($objet))
         ->setFrom('contact@codeassemblydev.fr')
         ->setTo($email)
         ->setBody(
            $content,
            'text/html'
         );

      $this->mailer->send($message);
   }
}
