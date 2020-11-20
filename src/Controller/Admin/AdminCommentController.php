<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/post/{id}/comment", name="admin.post.comment")
     */
    public function commentList(Post $post, CommentRepository $comment, EntityManagerInterface $manager): Response
    {
        $allComments = $comment->findBy([], ['createdAt' => 'desc']);
        return $this->render('admin/admin_comment/index.html.twig', [
            'controller_name' => 'AdminCommentController',
            'comments' => $allComments,
            'post' => $post
        ]);
    }



    /**
     * @Route("/admin/comments/{id}/publy", name="admin.post.comment.publy")
     */
    public function commentPubly(Comment $comment, EntityManagerInterface $manager, Request $request): Response
    {
        $commentId = $comment->getId();
        $comment->setPublished(1);
        $manager->persist($comment);
        $manager->flush();

        return new Response($commentId);
    }

    /**
     * @Route("/admin/comment/{id}/remove", name="admin.post.comment.remove")
     */
    public function commentremove(Comment $comment, EntityManagerInterface $manager): Response
    {
        $commentId = $comment->getId();
        $manager->remove($comment);
        $manager->flush();
        return new Response($commentId);
    }
}
