<?php

namespace App\DataFixtures;

use App\Entity\Groups;
use App\Entity\Trick;
use App\Entity\User;
use App\services\ActiveAccountService;
use App\services\SlugService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TricksFixtures extends Fixture
{

    private $encoder;
    private $activeToken;

    public function __construct(UserPasswordEncoderInterface $encoder, ActiveAccountService $activeToken)
    {
        $this->encoder = $encoder;
        $this->activeToken = $activeToken;
    }
    public function load(ObjectManager $manager)
    {
/*
 * create groups
 */

        $group = new Groups();
        $group->setName('No group');
        $group2 = new Groups();
        $group2->setName('Figure');

        $manager->persist($group);
        $manager->persist($group2);
/*
 * create many tricks
 */

        $slug = new SlugService();

        for ($i = 0; $i < 100; ++$i) {
            $trick = new Trick();
            $trick->setName('figure n° ' . $i)
                ->setSlug($slug->addSlug($trick->getName()))
                ->setText('je suis le texte de la figure n° ' . $i)
                ->setCreateDate(new \DateTime())
                ->setUpdateDate(null)
                ->setGroupsId($group);
            $manager->persist($trick);
        }
/*
 * create user admin
 */
        $admin = new User();
        $admin->setUserName('admin');
        $admin->setEmail('admin@admin.fr');
        $hash = $this->encoder->encodePassword($admin, "admin");
        $admin->setPassword($hash);
        $admin->setActive(true);
        $admin->setRoles(["ROLE_ADMIN"]);
        $this->activeToken->setUserToken($admin);
        $manager->persist($admin);

        $manager->flush();
    }
}
