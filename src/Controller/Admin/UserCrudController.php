<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateur')
            ->setEntityLabelInSingular('Utilisateurs')

            ->setPageTitle("index", "Jeux Studilympiques - Administration des utilisateurs")

            ->setPaginatorPageSize(10);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
            TextField::new('email'),
            TextField::new('password')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
            TextField::new('firstname'),
            TextField::new('lastname'),
            ArrayField::new('roles'),
            AssociationField::new('payments')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
            AssociationField::new('reservations')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),
        ];
    }
}
