<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DetailTrickController extends AbstractController
{
    /**
     * @Route("/trick/{slug}/{id}", name="detail_trick")
     * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
     */
    public function index(Trick $trick, Request $request, EntityManagerInterface $em)
    {

        $comments = $trick->getComments();
        $assets = $trick->getAssets();

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
            'comments' => $comments,
            'assets' => $assets,
        ]);
    }
}
