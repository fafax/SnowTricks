<?php

namespace App\Controller;

use App\Repository\AssetRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepo, AssetRepository $assetRepo)
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

}
