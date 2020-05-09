<?php

namespace App\Form;

use App\Entity\Receiver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ReceiverFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Your name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'You should have name'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-ZА-Я]{1}[a-zа-я]+$/',
                        'message' => 'Name is not valid'
                    ])
                ]
            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'label' => 'Surname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'You should have surname'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-ZА-Я]{1}[a-zа-я]+$/',
                        'message' => 'Surname is not valid'
                    ])
                ]
            ])
            ->add('patronymic', TextType::class, [
                'required' => false,
                'label' => 'Patronymic'
            ])
            ->add('passport', TextType::class, [
                'required' => false,
                'label' => 'Passport',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{4}\s?\d{6}?$/',
                        'message' => 'Input passport data in format 0123 456789'
                    ])
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => 'Phone number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Phone number field can not be empty'
                    ]),
                    new Regex([
                        'pattern' => '/\+?\d+([\(\s\-]?\d+[\)\s\-]?[\d\s\-]+)?/',
                        'message' => 'Not a valid phone number'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Receiver::class
        ]);
    }
}