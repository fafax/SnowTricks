<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepo)
    {
        $tricks = $trickRepo->findBy([], null, 15, 0);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/{page}", name="moreHome", requirements={"page"="\d+"})
     */
    public function more(int $page, TrickRepository $trickRepo)
    {
        $tricks = $trickRepo->findBy([], null, 15, $page);

        return $this->render('tricks.html.twig', [
            'controller_name' => 'Home',
            'tricks' => $tricks,
        ]);
    }

}
