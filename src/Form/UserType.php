<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\EventSubscriber\UserPasswordEncoderSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    private $userPasswordEncoderSubscriber;

    public function __construct(UserPasswordEncoderSubscriber $userPasswordEncoderSubscriber)
    {
        $this->userPasswordEncoderSubscriber = $userPasswordEncoderSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname',  TextType::class, ['label' => 'Nom'])
            ->add('email',     EmailType::class, ['label' => 'Email'])
            ->add('plaintextPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'La confirmation du mot de passe ne correspond pas',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Mot de passe (confirmation)'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8])
                ]
            ]);

        // on ajoute à notre formulaire notre objet de class d'écouteur UserPasswordEncoderSubscriber
        // qui a été injecté dans le constructeur
        $builder->addEventSubscriber($this->userPasswordEncoderSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
