<?php

namespace App\services;

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
                $this->params->get('picture_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $user->setAvatarFile($newFilename);

    }
}
