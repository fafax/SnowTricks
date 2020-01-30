<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Trick;
use App\Form\AssetType;
use App\Form\TrickType;
use App\Repository\AssetRepository;
use App\services\UploadImgService;
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
    public function index(Trick $trick, Request $request, EntityManagerInterface $em, AssetRepository $assetRepo, UploadImgService $upload)
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
        $asset = new Asset;

        $formAsset = $this->createForm(AssetType::class, $asset);
        $formAsset->handleRequest($request);

        if ($formAsset->isSubmitted() && $formAsset->isValid()) {
            $file = $formAsset['file']->getData();
            $type = $formAsset['type']->getData();
            $name = $formAsset['name']->getData();
            $url = $formAsset['url']->getData();
            if ($type === "youtube") {
                $upload->uploadAssetURL($url, $trick, $type, $name);

            } else {
                $upload->uploadAsset($file, $trick, $type, $name);
            }
            return $this->redirectToRoute('edit_detail_trick', ["id" => $trick->getId(), "slug" => $trick->getSlug()]);

        }

        if ($assetRepo->findOneBy(array('type' => 'image', 'trickId' => $trick->getId()))) {
            $mainAsset = $assetRepo->findOneBy(array('type' => 'image', 'trickId' => $trick->getId()))->getUrl();
        }
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'Edit detail Trick',
            'trick' => $trick,
            'assets' => $assets,
            'mainAsset' => $mainAsset,
            'form' => $form->createView(),
            'formAsset' => $formAsset->createView(),
        ]);
    }

    /**
     * @Route("/trick/update/{id}/{asset}", name="update_asset")
     * @ParamConverter("trick" ,options={"mapping" :{"id":"id"}})
     * @ParamConverter("asset" , class="App\Entity\Asset")
     */
    public function update(Trick $trick, Request $request, Asset $asset, UploadImgService $upload, EntityManagerInterface $em)
    {

        $formAsset = $this->createForm(AssetType::class, $asset);
        $formAsset->handleRequest($request);

        if ($formAsset->isSubmitted() && $formAsset->isValid()) {
            $file = $formAsset['file']->getData();
            $type = $formAsset['type']->getData();
            $name = $formAsset['name']->getData();
            if ($type === "youtube") {
                $url = $formAsset['url']->getData();
            }
            if ($file === null) {
                $asset->setType($type);
                $asset->setName($name);
                $asset->setUrl($url);
                $em->merge($asset);
                $em->flush();
            } else {
                $upload->updateAsset($asset, $file, $trick, $type, $name);
            }
            return $this->redirectToRoute('edit_detail_trick', ["id" => $trick->getId(), "slug" => $trick->getSlug()]);

        }

        return $this->render('administration/update.asset.html.twig', [
            'controller_name' => 'Update asset',
            'trick' => $trick,
            'asset' => $asset,
            'formAsset' => $formAsset->createView(),
        ]);
    }

    /**
     * @Route("/trick/delete/{id}", name="delete_asset", methods="DELETE" )
     */

    public function deleteAsset(Asset $asset, EntityManagerInterface $em, Request $request, UploadImgService $upload)
    {

        if ($this->isCsrfTokenValid('delete' . $asset->getId(), $request->get('_token'))) {
            $trick = $asset->getTrickId();
            // $upload->deleteFile($asset->getUrl());
            $em->remove($asset);
            $em->flush();
        }

        return $this->redirectToRoute('edit_detail_trick', ["id" => $trick->getId(), "slug" => $trick->getSlug()]);
    }

    /**
     * @Route("/trick/delete/{id}/", name="delete_trick" , methods="DELETE" )
     */
    public function deleteTrick(Trick $trick, EntityManagerInterface $em, Request $request)
    {

        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->get('_token'))) {

            $em->remove($trick);
            $em->flush();

        }

        return $this->redirectToRoute('home');
    }
}
