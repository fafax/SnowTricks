<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailService $email)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $content = $this->renderView('email/createCount.html.twig', [
                "user" => $user,
            ]);
            $em->persist($user);
            $email->sendEmail($user->getEmail(), 'essaie', $content);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }
}
