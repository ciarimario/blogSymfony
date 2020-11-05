<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{slug}", name="post.index")
     */
    public function index(Post $post): Response
    {

        return $this->render('post/index.html.twig', [
            'post' => $post,
        ]);
    }
}
