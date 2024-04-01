<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Réservation')
            ->setEntityLabelInSingular('Réservations')

            ->setPageTitle("index", "Jeux Studilympiques - Administration des réservations")

            ->setPaginatorPageSize(10);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
            TextField::new('firstname'),
            TextField::new('lastname'),
            NumberField::new('number_of_ticket'),
            TextField::new('ticket')
                ->hideOnIndex(),
            BooleanField::new('isPaid'),
            CollectionField::new('offer')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
            AssociationField::new('user')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
            AssociationField::new('payment')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
        ];
    }
}
