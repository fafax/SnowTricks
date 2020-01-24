<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\AssetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DetailTrickController extends AbstractController
{
    /**
     * @Route("/trick/{slug}/{id}", name="detail_trick")
     * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
     */
    public function index(Trick $trick, Request $request, EntityManagerInterface $em, AssetRepository $assetRepo)
    {

        $assets = $trick->getAssets();
        $mainAsset = null;

        if ($request->request->has("comment")) {
            $comment = new Comment();
            $comment->setComment($request->request->get('comment'));
            $comment->setCreateDate(new \DateTime());
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
        }
        if ($assetRepo->findOneBy(array('type' => 'image', 'trickId' => $trick->getId()))) {
            $mainAsset = $assetRepo->findOneBy(array('type' => 'image', 'trickId' => $trick->getId()))->getUrl();
        }

        $comments = array_slice(array_reverse($trick->getComments()->toArray()), 0, 10);
        $count = count($trick->getComments()->toArray());

        $url = $this->generateUrl('more_comment', ["slug" => $trick->getSlug(), "id" => $trick->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('detail_trick/index.html.twig', [
            'controller_name' => 'Detail Trick',
            'trick' => $trick,
            'comments' => $comments,
            'assets' => $assets,
            'mainAsset' => $mainAsset,
            'url' => $url,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/trick/{slug}/{id}/comment/{comment}", name="more_comment")
     */
    public function more(Trick $trick, int $comment = 0)
    {

        $comments = array_reverse($trick->getComments()->toArray());
        $comments = array_slice($comments, 5 + $comment, 5);

        return $this->render('comments.html.twig', [
            'controller_name' => 'Home',
            'comments' => $comments,
        ]);
    }
}
