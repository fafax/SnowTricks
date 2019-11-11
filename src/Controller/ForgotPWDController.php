<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgotPWDController extends AbstractController
{

    /**
     * @Route("/forgot/", name="forgot")
     */
    public function index(Request $request, UserRepository $userRepo)
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
