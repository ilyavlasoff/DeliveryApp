<?php

namespace App\Form;

use App\Entity\Auto;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AutoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'required' => true,
                'label' => 'State number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Number can not be blank'
                    ]),
                    new Regex([
                        'pattern' => '/^\w{8}$/',
                        'message' => 'State number is not valid'
                    ])
                ]
            ])
            ->add('model', TextType::class, [
                'required' => true,
                'label' => 'Model',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Model field can not be blank'
                    ])
                ]
            ])
            ->add('requiredDriveCat', TextType::class, [
                'required' => true,
                'label' => 'Driving category',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Drive categories can not be blank'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Z]+$/',
                        'message' => 'Invalid driving category'
                    ])
                ]
            ])
            ->add('capacity', NumberType::class, [
                'required' => true,
                'label' => 'Auto capacity',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Auto capacity can not be blank'
                    ]),
                    new Regex([
                        'pattern' => '/\d+/',
                        'message' => 'Capacity must be a numeric value'
                    ])
                ]
            ])
            ->add('isFunctional', CheckboxType::class, [
                'label' => 'Functional'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => Auto::class
            ]);
    }
}