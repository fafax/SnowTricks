<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActiveAccountController extends AbstractController
{
    /**
     * @Route("/active/{token}", name="active")
     */
    public function index(UserRepository $users, $token, EntityManagerInterface $em)
    {
        $user = $users->findOneBy(array("token" => $token));

        if ($user != null) {
            if ($user->getActive() === false) {
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
}
