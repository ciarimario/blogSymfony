<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    
    /**
     * @Route("/post/{slug}", name="post.index")
     */
    public function index(Post $post, Request $request, EntityManagerInterface $manager): Response
    {

        // CommentType::class renvoie le namespace de CommentType
        // on crée un form de la class CommentType
        $form = $this->createForm(CommentType::class);
        // on transmet les données de la reqête Http à $form
        $form->handleRequest($request);
        // si le form est soumis et valide



        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les données du formulaire
            $comment = $form->getData();

            // on donnes à la prorpriété post de comment  l'entité $post
            $comment->setPost($post);
            // on fait persisté le $comment
            $manager->persist($comment);
            // on lance toutes les modifications vers la BDD
            $manager->flush();

            // on ajoute un message flash
            $this->addFlash('success', 'Votre commentaire a bien été ajouté!');

            // on fait une redirection en get vers la même page pour que les champs du form en post 
            // sur la page de l'internaute se vident
            return $this->redirect($request->headers->get('referer'));
        }
        
        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/{slug}/add-comment", name="post.ajax.comment", methods={"POST"})
     */
    public function ajaxAddComment(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment = $form->getData();
            $comment->setPost($post);

            $manager->persist($comment);
            $manager->flush();

            return $this->render('post/_comment.html.twig', [
                'comment' => $comment
            ]);
        }
    }




}
