<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DetailTrickController extends AbstractController
{
    /**
     * @Route("/trick/{slug}/{id}", name="detail_trick")
     * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
     */
    public function index(Trick $trick ,UserRepository $userRepo)
    {
   
      $comments = $trick->getComments();

      foreach ($comments as $comment ) {
         
         dump($comment->getUser());
      }
         
      
        return $this->render('detail_trick/index.html.twig', [
            'controller_name' => 'Detail Trick',
            'trick'=> $trick,
            "comments" => $comments
        ]);
    }
}
