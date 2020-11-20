<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use App\Factory\UserFactory;
use App\Factory\CommentFactory;
use App\Factory\CategoryFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)

    {

        // Création de 5 users
        UserFactory::new()->createMany(5);

        // Création de 5 catégories grâce à la CategoryFactory, l'usine à fabriquer des catégories
        CategoryFactory::new()->createMany(5);
            
        // Création de 10 articles grâce à la PostFactory, l'usine à fabriquer des articles
        PostFactory::new()->createMany(10);

        // Création de 5 commentaires grâce à la CommentFactory, l'usine à commentaires
        CommentFactory::new()->createMany(20);
        // Enregistrement des objets créés en base de données

        // on créé un administrateur
        UserFactory::new()->create([
            'roles' => ['ROLE_ADMIN'],
            'password' => 'admin',
            'email' => 'admin@admin.com'
        ]);
        
        $manager->flush();
    }
}
