<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/new", name="user.new")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class);
        // on lie le form avec les données de la requête
        $form->handleRequest($request);
        // si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // formate les données du formulaire en un objet $user de l'entity User
            // transforme les données du form en entity User
            $user = $form->getData();
            /*  // on récupère le mot de passe en clair
            $plainTextPassword = $form->get('plaintextPassword')->getData();
            // on hash le password
            $hashedPassword = $encoder->encodePassword($user, $plainTextPassword); */
            // on assigne la valeur du mot de passe à la propriété password de l'objet $user
            // qui est de la class, de l'entity User
            /* $user->setPassword($hashedPassword); */
            // on fait persister notre user
            $manager->persist($user);
            // écrit dans la BDD
            $manager->flush();
            // envoie d'un message flash
            $this->addFlash('success', 'Votre copmpte a bien été créé, vous pouvez vous connecter.');
            // redirection vers la route secutity.login
            return $this->redirectToRoute('security.login');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
