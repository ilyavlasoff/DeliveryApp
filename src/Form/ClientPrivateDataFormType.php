<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ClientPrivateDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'Username',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Username can not be blank'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Username must contains at least {{ limit }} symbols',
                        'max' => 180,
                        'maxMessage' => 'Username can not contains more {{ limit }} symbols'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z01-9_-]+$/',
                        'message' => 'Username must contain only digits, words A-Za-z and underscores'
                    ])
                ]
            ])
            ->add('email', TextType::class, [
                'required' => true,
                'label' => 'E-mail',
                'constraints' => [
                    new Email([
                        'message' => 'It seems it is not a valid e-mail'
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z]{1}[a-z]+$/',
                        'message' => 'This is not a valid name'
                    ])
                ]
            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'label' => 'Surname',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z]{1}[a-z]+$/',
                        'message' => 'This is not a valid surname'
                    ])
                ]
            ])
            ->add('patronymic', TextType::class, [
                'required' => false,
                'label' => 'Patronymic',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z]{1}[a-z]+$/',
                        'message' => 'This is not a valid patronymic'
                    ])
                ]
            ])
            ->add('passport', TextType::class, [
                'required' => true,
                'label' => 'Passport data',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{4}\s?\d{6}?$/',
                        'message' => 'This is not a valid passport code'
                    ])
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => 'Phone number',
                'constraints' => [
                    new Regex([
                        'pattern' => '/\+?\d+([\(\s\-]?\d+[\)\s\-]?[\d\s\-]+)?/',
                        'message' => 'This is not a valid phone number'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'=>false
        ]);
    }
}