<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Extension\Core\DataMapper\SubmittedForm;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddWorkshiftFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, [
                'required' => true,
                'label' => 'Start date',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Starting date can not be blank'
                    ])
                ]
            ])
            ->add('startTime', TextType::class, [
                'required' => true,
                'label' => 'Start time',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Starting time can not be blank'
                    ])
                ]
            ])
            ->add('endDate', TextType::class, [
                'required' => true,
                'label' => 'End date',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Starting date can not be blank'
                    ])
                ]
            ])
            ->add('endTime', TextType::class, [
                'required' => true,
                'label' => 'End time',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Starting time can not be blank'
                    ])
                ]
            ])
            ->add('autoNum', TextType::class, [
                'required' => true,
                'label' => 'Auto',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Starting time can not be blank'
                    ])
                ]
            ])
            ->add('courierId', TextType::class, [
                'required' => true,
                'label' => 'Courier',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Starting time can not be blank'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create workshift'
            ]);
    }
}