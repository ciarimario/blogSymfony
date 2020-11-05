<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        PostFactory::new()->createMany(10);

        // Enregistrement des objets créés en base de données
        $manager->flush();
    }
}
