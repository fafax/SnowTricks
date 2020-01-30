<?php

namespace App\services;

use App\Entity\Asset;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadImgService
{

    private $em;
    private $params;

    private $filePath;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->params = $params;
        $this->file = new Filesystem();

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

    public function uploadAsset($data, Trick $trick, $type, $name)
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

        $asset->setname($name);
        $asset->setType($type);
        $asset->setUrl($newFilename);
        $asset->setTrickId($trick);

        $this->em->persist($asset);
        $this->em->flush();

    }

    public function uploadAssetURL($url, Trick $trick, $name)
    {
        $asset = new Asset();

        $asset->setname($name);
        $asset->setType('youtube');
        $asset->setUrl($url);
        $asset->setTrickId($trick);

        $this->em->persist($asset);
        $this->em->flush();
    }

    public function updateAsset(Asset $asset, $data, Trick $trick, $type, $name)
    {
        $asset = $asset;
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

        $asset->setname($name);
        $asset->setType($type);
        $asset->setUrl($newFilename);
        $asset->setTrickId($trick);

        $this->em->merge($asset);
        $this->em->flush();

    }

    public function deleteFile(string $asset)
    {

        $result = $this->file->remove($asset);
        if ($result === false) {
            throw new \Exception(sprintf('Error deleting "%s"', $asset));
        }

    }
}
