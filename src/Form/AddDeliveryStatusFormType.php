<?php

namespace App\Form;

use App\Entity\StatusHistory;
use App\Service\DeliveryOperationService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddDeliveryStatusFormType extends AbstractType
{
    private $deliveryDBOperator;

    public function __construct(DeliveryOperationService $deliveryDBOperator)
    {
        $this->deliveryDBOperator = $deliveryDBOperator;
    }

    public function getListOfAvailableStatusCodes():array
    {
        return $this->deliveryDBOperator->getDeliveryStatusCodes();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery', HiddenType::class, [
                'required' => true
            ])
            ->add('statusComment', TextareaType::class, [
                'required' => false,
                'label' => 'Comment',
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'You cannot add {{ limit }} symbols to comment'
                    ])
                ]
            ])
            ->add('statusCode', ChoiceType::class, [
                'required' => false,
                'choices' => $this->getListOfAvailableStatusCodes(),
                'placeholder' => 'Choose status code'
            ])
            ->add('submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StatusHistory::class
        ]);
    }
}