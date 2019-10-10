<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepo)
    {
       $tricks = $trickRepo->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'tricks' => $tricks
        ]);
    }
}
