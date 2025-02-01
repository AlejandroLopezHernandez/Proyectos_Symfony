<?php

namespace App\Controller\Admin;

use App\Entity\Agenda;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AgendaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Agenda::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre','Objetivo'),
            AssociationField::new('tarea','Tarea')
            ->setFormTypeOption('by_reference',false),
        ];
    }
    
}
