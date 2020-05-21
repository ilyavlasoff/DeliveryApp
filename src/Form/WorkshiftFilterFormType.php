<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class WorkshiftFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', CheckboxType::class, [
                'label' => 'Active workshifts only',
                'required' => false
            ])
            ->add('fromDate', TextType::class, [
                'label' => 'Before: ',
                'required' => false
            ])
            ->add('toDate', TextType::class, [
                'label' => 'Later: ',
                'required' => false
            ])
            ->add('courierID', TextType::class, [
                'label' => 'Courier ID',
                'required' => false
            ])
            ->add('courierSurname', TextType::class, [
                'label' => 'Courier\'s surname',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Find'
            ]);
    }
}