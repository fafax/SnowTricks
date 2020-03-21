<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\services\MailService;
use App\services\UploadImgService;
use App\services\ActiveAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/active/{token}", name="active")
     */
    public function ActiveAccount(UserRepository $users, $token, EntityManagerInterface $em)
    {
        $user = $users->findOneBy(array("token" => $token));

        if ($user != null) {
            if (!$user->getActive()) {
                $user->setActive(true);
                $em->persist($user);
                $em->flush();
                return $this->render('active_account/index.html.twig', [
                    'controller_name' => 'ActiveAccountController',
                ]);
            } else {
                return $this->render('active_account/already.html.twig', [
                ]);
            }
        }

        return $this->render('active_account/error.html.twig', [
            'controller_name' => 'ActiveAccountController',
        ]);
    }
    /**
     * @Route("/forgot/", name="forgot")
     */
    public function forgotPWD(Request $request, UserRepository $userRepo)
    {

        $form = $this->createFormBuilder()
            ->add('ForgotUserName', TextType::class,
                ['label' => 'Username'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $userRepo->findOneBy(array('userName' => $form->getData()));
            if ($user != null) {
                return $this->redirectToRoute('reset');

            } else {
                $this->addFlash('danger', 'Utilisateur introuvable');
            }
        }

        return $this->render('forgot_pwd/index.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset/", name="reset")
     */
    public function resetPWD(Request $request, UserRepository $userRepo, UserPasswordEncoderInterface $encoder, EntityManagerInterface $userManager)
    {

        $form = $this->createFormBuilder()
            ->add('ForgotUserName', TextType::class,
                ['label' => 'Username'])
            ->add('ForgotPWD', PasswordType::class,
                ['label' => 'Password'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $userRepo->findOneBy(array('userName' => $form->get('ForgotUserName')->getData()));
            if ($user != null) {
                $hash = $encoder->encodePassword($user, $form->get('ForgotPWD')->getData());
                $user->setPassword($hash);
                $userManager->persist($user);
                $userManager->flush();
                return $this->redirectToRoute("home");
            } else {
                $this->addFlash('danger', "Nom de l'utilisateur incorrect");
            }
        }

        return $this->render('reset_pwd/index.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
