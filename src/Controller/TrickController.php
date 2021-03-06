<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\AssetRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(TrickRepository $trickRepo, AssetRepository $assetRepo)
    {
        $tricks = $trickRepo->findBy([], null, 15, 0);

        $tricks = $this->addHomeAsset($assetRepo, $tricks);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'tricks' => $tricks,
            'url' => $this->generateUrl('moreHome', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @Route("/{page}", name="moreHome", requirements={"page"="\d+"})
     */
    public function more(int $page = 0, TrickRepository $trickRepo, AssetRepository $assetRepo)
    {

        $tricks = $trickRepo->findBy([], null, 15, $page);

        $tricks = $this->addHomeAsset($assetRepo, $tricks);

        return $this->render('tricks.html.twig', [
            'controller_name' => 'Home',
            'tricks' => $tricks,
        ]);
    }

    private function addHomeAsset($repoAsset, $tricks)
    {
        for ($i = 0; $i < count($tricks); $i++) {
            $homeAsset = $repoAsset->findOneBy(array('trickId' => $tricks[$i]->getId(), 'type' => 'image'));
            if ($homeAsset !== null) {
                $homeAsset = $homeAsset->getUrl();
                $tricks[$i]->setHomeAsset($homeAsset);

            }
        }
        return $tricks;

    }

    /**
     * @Route("/trick/{slug}/{id}", name="detail_trick")
     * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
     */
    public function detail(Trick $trick, Request $request, EntityManagerInterface $em, AssetRepository $assetRepo)
    {

        $assets = $trick->getAssets();
        $mainAsset = null;

        if ($request->request->has("comment")) {
            $this->addComment($request, $em, $trick);
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
    public function moreComment(Trick $trick, int $comment = 0)
    {

        $comments = array_reverse($trick->getComments()->toArray());
        $comments = array_slice($comments, 5 + $comment, 5);

        return $this->render('comments.html.twig', [
            'controller_name' => 'Home',
            'comments' => $comments,
        ]);
    }

    private function addComment($request, $em, $trick)
    {
        $comment = new Comment();
        $comment->setComment($request->request->get('comment'));
        $comment->setCreateDate(new \DateTime());
        $comment->setTrick($trick);
        $comment->setUser($this->getUser());
        $em->persist($comment);
        $em->flush();
    }
}
