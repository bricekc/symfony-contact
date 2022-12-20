<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname'),
            TextField::new('lastname'),
            EmailField::new('email'),
            TelephoneField::new('phone'),
            AssociationField::new('category')->formatValue(function ($value, $entity) {
                return $entity->getCategory()?->getName() ?? 'pas de Catégorie';
            })
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('query_builder', function ($er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }),
        ];
    }
}
