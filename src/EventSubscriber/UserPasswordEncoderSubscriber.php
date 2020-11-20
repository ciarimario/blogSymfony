<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordEncoderSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function onFormPostSubmit($event)
    {
        // à partir de l'objet $event qui est l'écouteur sur la soumission du form
        // on récupère le form
        $form = $event->getForm();
        // on récupère les données du form que l'on transforme en objet entity User
        $user = $event->getData();
        $plainTextPassword = $form->get('plaintextPassword')->getData();

        if ($plainTextPassword) {
            $hashedPassword = $this->encoder->encodePassword($user, $plainTextPassword);
            $user->setPassword($hashedPassword);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'form.post_submit' => 'onFormPostSubmit',
        ];
    }
}
