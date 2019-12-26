<?php

namespace App\DataFixtures;

use App\Entity\Groups;
use App\Entity\Trick;
use App\services\SlugService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $slug = new SlugService();
        $group = new Groups();
        $group->setName('pas de groupe');
        $manager->persist($group);

        for ($i = 0; $i < 200; ++$i) {
            $trick = new Trick();
            $trick->setName('figure n° ' . $i)
                ->setSlug($slug->addSlug($trick->getName()))
                ->setText('je suis le texte de la figure n° ' . $i)
                ->setCreateDate(new \DateTime())
                ->setUpdateDate(null)
                ->setGroupsId($group);
            $manager->persist($trick);
        }
        $manager->flush();
    }
}
