<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Old password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Old password can not be blank'
                    ]),
                    new UserPassword([
                        'message' => 'Old password is incorrect'
                    ])
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'New password'],
                'second_options' => ['label' => 'Repeat new password'],
                'invalid_message' => 'The password fields must match',
                'constraints' => [
                    new Length([
                        'max' => 4096,
                        'maxMessage' => 'Too much symbols in new password',
                        'min' => 6,
                        'minMessage' => 'Password length must be at least {{ limit }} symbols'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z01-9_-]{6,}$/',
                        'message' => 'Password contains restricted symbols'
                    ]),
                    new NotCompromisedPassword([
                        'message' => 'It seems password may be compromised'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Change'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'=>false
        ]);
    }
}