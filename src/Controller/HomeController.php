<?php

namespace App\Controller;

use stdClass;
use Faker\Factory;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home.index")
     */
    public function index(PostRepository $postRepository): Response
    {
        // stocke le tableau des objets article retourné par createPost()
        $posts = $postRepository->findBy([], ['createdAt' => 'desc']);
        return $this->render('home/index.html.twig', [

            'posts' => $posts
        ]);
    }

    // création de fausse données
    public function createPost(): array
    {
        // instanciation d'un objet de la classe Factory de la Libraireie Faker
        // Cette librairie renvoie du contenu aléatoire
        $faker = Factory::create('fr_FR');

        // tableau qui stockera les objets créés
        $posts = [];

        // création aléatoire de $i objets
        for ($i = 0; $i < 10; $i++) {
            $object = new stdClass();
            $object->title = $faker->sentence();
            $object->content = $faker->text(2500);
            $object->image = 'https://picsum.photos/seed/post-' . $i . '/750/300';
            $object->createdAt = $faker->dateTimeBetween('-3 years', 'now', 'Europe/Paris');
            $object->author = $faker->name();

            // tableau qui stocke les objets article
            array_push($posts, $object);
        }
        return $posts;
    }
}
