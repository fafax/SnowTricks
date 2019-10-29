<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use App\services\MailService;

use App\services\UploadImgService;

use App\services\ActiveAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */

    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UploadImgService $upload, MailService $email,ActiveAccountService $activeToken)

    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            if ($form->get('avatarFile')->getData()) {
                $data = $form->get('avatarFile')->getData();
                $upload->uploadAvatar($data, $user);
            }
            $user->setActive(false);
            $activeToken->setUserToken($user);
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
