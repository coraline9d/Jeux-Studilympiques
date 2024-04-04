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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('number_of_ticket', IntegerType::class, [
            'label' => 'Nombre de Pass :',
            'attr' => [
                'id' => 'number_of_ticket',
                'min' => 1,
                'placeholder' => 'Entrez nombre de Pass'
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
            ->add('offer', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Offer::class,
                    'choice_label' => 'name',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Offre :',
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
            'number_of_ticket'=> null,
        ]);
    }
}
