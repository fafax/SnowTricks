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

    private $em;
    private $assetRepo;
    private $upload;

    public function __construct(EntityManagerInterface $em, AssetRepository $assetRepo, UploadImgService $upload)
    {
        $this->em = $em;
        $this->assetRepo = $assetRepo;
        $this->upload = $upload;

    }

    /**
     * @Route("/trick/edit/{slug}/{id}", name="edit_detail_trick")
     * @ParamConverter("trick" ,options={"mapping" :{"slug":"slug","id":"id"}})
     */
    public function index(Trick $trick, Request $request)
    {

        $assets = $trick->getAssets();
        $mainAsset = null;

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($trick);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }
        $asset = new Asset;

        $formAsset = $this->createForm(AssetType::class, $asset);
        $formAsset->handleRequest($request);

        if ($formAsset->isSubmitted() && $formAsset->isValid()) {
            $this->setAsset($formAsset, $trick);
        }

        if ($this->assetRepo->findOneBy(array('type' => 'image', 'trickId' => $trick->getId()))) {
            $mainAsset = $this->assetRepo->findOneBy(array('type' => 'image', 'trickId' => $trick->getId()))->getUrl();
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
    public function update(Trick $trick, Request $request, Asset $asset)
    {
        $this->upload->deleteFile($asset->getUrl());
        $formAsset = $this->createForm(AssetType::class, $asset);
        $formAsset->handleRequest($request);

        if ($formAsset->isSubmitted() && $formAsset->isValid()) {
            $this->updateAsset($formAsset, $asset, $trick);
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
     * @Route("/trick/delete/asset/{id}", name="delete_asset", methods="DELETE" )
     */
    public function deleteAsset(Asset $asset, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $asset->getId(), $request->get('_token'))) {
            $trick = $asset->getTrickId();
            $this->upload->deleteFile($asset->getUrl());
            $this->em->remove($asset);
            $this->em->flush();
        }

        $this->addFlash('success', 'Delete asset success');

        return $this->redirectToRoute('edit_detail_trick', ["id" => $trick->getId(), "slug" => $trick->getSlug()]);
    }

    /**
     * @Route("/trick/delete/{id}/", name="delete_trick" , methods="DELETE" )
     */
    public function deleteTrick(Trick $trick, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->get('_token'))) {
            $assets = $this->assetRepo->findby(["trickId" => $trick->getId()]);
            for ($i = 0; $i < count($assets); $i++) {
                $this->upload->deleteFile($assets[$i]->getUrl());
                $this->em->remove($assets[$i]);
            }
            $this->em->remove($trick);
            $this->em->flush();
        }
        $this->addFlash('success', 'Delete trick success');

        return $this->redirectToRoute('home');
    }

    /*
     * create new asset based on option
     */
    private function setAsset($formAsset, $trick)
    {
        $file = $formAsset['file']->getData();
        $type = $formAsset['type']->getData();
        $name = $formAsset['name']->getData();
        $url = $formAsset['url']->getData();
        if ($type === "youtube") {
            $this->upload->uploadAssetURL($url, $trick, $type, $name);
        } else {
            $this->upload->uploadAsset($file, $trick, $type, $name);
        }

        $this->addFlash('success', 'Create asset success');

    }

    /*
     * update asset based on option
     */
    private function updateAsset($formAsset, $asset, $trick)
    {
        $file = $formAsset['file']->getData();
        $type = $formAsset['type']->getData();
        $name = $formAsset['name']->getData();

        if ($file === null && $type === "youtube") {
            $asset->setType($type);
            $asset->setName($name);
            $asset->setUrl($formAsset['url']->getData());
            $this->em->merge($asset);
            $this->em->flush();
        } else {
            $this->upload->updateAssetService($asset, $file, $trick, $type, $name);
        }
        $this->addFlash('success', 'Update asset success');
    }
}
