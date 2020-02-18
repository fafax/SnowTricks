<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\services\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddTrickController extends AbstractController
{
    /**
     * @Route("/add/trick", name="add_trick")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        $slug = new SlugService();

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setSlug($slug->addSlug($trick->getName()))
                ->setCreateDate(new \DateTime());
            $em->persist($trick);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('add_trick/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
