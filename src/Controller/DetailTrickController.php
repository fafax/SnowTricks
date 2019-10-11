<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DetailTrickController extends AbstractController
{
   /**
    * @Route("/trick/{slug}/{id}", name="detail_trick")
    * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
    */
   public function index(Trick $trick, Request $request, EntityManagerInterface $em)
   {

      $comments = $trick->getComments();

      if ($request->request->has("comment")) {
         $comment = new Comment();
         $comment->setComment($request->request->get('comment'));
         $comment->setCreateDate(new \DateTime());
         $comment->setTrick($trick);
         $comment->setUser($this->getUser());
         $em->persist($comment);
         $em->flush();
      }

      return $this->render('detail_trick/index.html.twig', [
         'controller_name' => 'Detail Trick',
         'trick' => $trick,
         "comments" => $comments
      ]);
   }
}
