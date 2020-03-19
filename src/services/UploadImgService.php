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

    private $filesystem;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, Filesystem $filesystem)
    {
        $this->em = $em;
        $this->params = $params;
        $this->filesystem = $filesystem;

    }

    /*
     * Upload image in server and generate unique file name
     */
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

    /*
     * Upload image in server and generate unique file name
     */
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

    /*
     * put url for the youtube link in database
     */

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

/*
 * update asset
 */
    public function updateAssetService(Asset $asset, $data, Trick $trick, $type, $name)
    {
        $pictureFile = $data;
        $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

        try {
            $pictureFile->move(
                $this->params->get('assets_directory'),
                $newFilename
            );
        } catch (FileException $e) {

        }
        $asset->setname($name);
        $asset->setType($type);
        $asset->setUrl($newFilename);
        $asset->setTrickId($trick);

        $this->em->merge($asset);
        $this->em->flush();
    }

/*
 * Remove asset in server
 */
    public function deleteFile(string $assetName)
    {
        $this->filesystem->remove($this->params->get('assets_directory') . '/' . $assetName);
    }
}
