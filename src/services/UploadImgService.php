<?php

namespace App\services;

use App\Entity\Asset;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UploadImgService
{

    private $em;
    private $params;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->params = $params;
    }

    public function uploadAvatar($data, User $user)
    {

        $pictureFile = $data;
        $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

        try {
            $pictureFile->move(
                $this->params->get('avatar_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $user->setAvatarFile($newFilename);

    }

    public function uploadAsset($data, Trick $trick)
    {
        $asset = new Asset();
        $pictureFile = $data;
        $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

        try {
            $pictureFile->move(
                $this->params->get('assets_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $asset->setname("test");
        $asset->setType("image");
        $asset->setUrl($newFilename);
        $asset->setTrickId($trick->getId());

        $this->em->persist($asset);
        $this->em->fluch();

    }
}
