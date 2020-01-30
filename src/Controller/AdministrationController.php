<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\AssetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/trick/edit/{slug}/{id}", name="edit_detail_trick")
     * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
     */
    public function index(Trick $trick, Request $request, EntityManagerInterface $em, AssetRepository $assetRepo)
    {

        $assets = $trick->getAssets();
        $mainAsset = null;

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($trick);
            $em->flush();
            return $this->redirectToRoute('home');

        }

        if ($assetRepo->findOneBy(array('type' => 'image'))) {
            $mainAsset = $assetRepo->findOneBy(array('type' => 'image'))->getUrl();
        }

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'Edit detail Trick',
            'trick' => $trick,
            'assets' => $assets,
            'mainAsset' => $mainAsset,
            'form' => $form->createView(),
        ]);
    }
}
