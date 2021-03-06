<?php

namespace App\services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ActiveAccountService
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*
     * Generate token for active account
     */
    private function generateToken()
    {
        $unique = \uniqid();
        $token = md5($unique);
        return $token;
    }

    /*
     * Set token in database for active account
     */

    public function setUserToken(User $user)
    {
        $user->setToken($this->generateToken());
        $this->em->persist($user);
        $this->em->flush();
    }
}
