<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.index")
     */
    public function index(PostRepository $post): Response
    {
        $posts = $post->findBy([], ['createdAt' => 'desc']);
        return $this->render('admin/base.admin.html.twig', [
            'posts' => $posts,
        ]);
    }
}
