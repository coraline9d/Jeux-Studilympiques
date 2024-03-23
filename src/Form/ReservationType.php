<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Offer;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('number_of_ticket', IntegerType::class, [
            'label' => 'Nombre de Pass :',
            'attr' => [
                'id' => 'number_of_ticket',
                'min' => 1,
                'value'=>1
            ],
            'constraints' => [
                new LessThanOrEqual([
                    'value' => 30,
                    'message' => 'Vous ne pouvez pas réserver plus de 30 pass'
                ]),
                new NotBlank([
                    'message' => 'Veuillez renseigner le nombre de pass s\'il vous plait'
                ])
            ],
        ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 60,
                        'message' => 'Votre prénom ne peut pas faire plus de 60 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre prénom'
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom :',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 60,
                        'message' => 'Votre nom ne peut pas faire plus de 60 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre nom'
                    ]),
                ]
            ])
            ->add('offer', EntityType::class, [
                'class' => Offer::class,
                'label' => 'Offre :',
                'choice_label' => 'name',
                'placeholder'=>'Choisissez un pass',
                'mapped' => false,
                'data' => $options['selected_offer'], // Utilisation de l'option 'selected_offer' pour pré-sélectionner l'offre
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un pass'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'selected_offer' => null, // Ajout de l'option 'selected_offer'
        ]);
    }
}
