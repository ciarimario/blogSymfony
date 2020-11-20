<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use Gedmo\Sluggable\Util\Urlizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/admin/post/new", name="admin.post.new")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostType::class);
        // on lie le form avec les données de la requête
        $form->handleRequest($request);

        // si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // formate les données du formulaire en un objet $user de l'entity User
            // transforme les données du form en entity Post
            $post = $form->getData();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('post_image_directory');
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $post->setImage($newFilename);
            // grâce à la méthode getUser hérité de AbstractController
            // on récupère l'utilisateur connecté
            $post->setUser($this->getUser());
            /*  // on récupère le mot de passe en clair
            $plainTextPassword = $form->get('plaintextPassword')->getData();
            // on hash le password
            $hashedPassword = $encoder->encodePassword($user, $plainTextPassword); */
            // on assigne la valeur du mot de passe à la propriété password de l'objet $user
            // qui est de la class, de l'entity User
            /* $user->setPassword($hashedPassword); */
            // on fait persister notre user
            $manager->persist($post);
            // écrit dans la BDD
            $manager->flush();
            // envoie d'un message flash
            $this->addFlash('success', 'Votre article a bien été créé');
            // redirection vers la route secutity.login
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/new.html.twig', [

            'form' => $form->createView(),

        ]);
    }

    /**
     * 
     * @Route("/admin/post/edit/{id}", name="admin.post.edit")
     */
    public function edit(Post $post, Request $request, EntityManagerInterface $manager, Filesystem $filesystem)
    {
        // on créé le formulaire et on lui transmet le $post sélectionné grâce à l'id de la route
        $form = $this->createForm(PostType::class, $post);
        // on lie le form avec les données de la requête
        $form->handleRequest($request);

        // si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // formate les données du formulaire en un objet $user de l'entity User
            // transforme les données du form en entity Post
            $post = $form->getData();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            // on vérifie si il ya déjà une image dans le dossier public/upload/post/image pour cette objet $post
            if ($currentFilename = $post->getImage()) {

                $currentPath = $this->getParameter('post_image_directory') . '/' . $currentFilename;

                if ($filesystem->exists($currentPath)) {
                    $filesystem->remove($currentPath);
                }
                // ...
            }

            $destination = $this->getParameter('post_image_directory');
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $post->setImage($newFilename);
            // grâce à la méthode getUser hérité de AbstractController
            // on récupère l'utilisateur connecté
            $post->setUser($this->getUser());

            $manager->persist($post);
            // écrit dans la BDD
            $manager->flush();
            // envoie d'un message flash
            $this->addFlash('success', 'Votre article a bien été créé');
            // redirection vers la route secutity.login
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/edit.html.twig', [

            'form' => $form->createView(),
            'post' => $post


        ]);
    }

    /**
     * 
     * @Route("/admin/post/remove/{id}", name="admin.post.remove")
     */
    function remove(Post $post, Request $request, EntityManagerInterface $manager, Filesystem $filesystem)
    {
        // on récupère l'id du post
        $postId = $post->getId();






        // on vérifie si il y a une image dans le dossier public/upload/post/image pour cette objet $post
        if ($currentFilename = $post->getImage()) {

            $currentPath = $this->getParameter('post_image_directory') . '/' . $currentFilename;

            if ($filesystem->exists($currentPath)) {
                $filesystem->remove($currentPath);
            }
            // on supprime le post correspondant à l'id dans la route
            $manager->remove($post);
            $manager->flush();

            // ...
        }

        // Vérification AJAX
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            // on renvoie une réponse avec l'id du post supprimé
            return new response($postId);
        } else {

            // envoie d'un message flash
            $this->addFlash('success', 'Votre article a bien été supprimé');
            // redirection vers la route secutity.login
            return $this->redirectToRoute('admin.index');
        }
    }
}
