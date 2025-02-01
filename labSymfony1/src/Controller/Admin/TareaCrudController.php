<?php

namespace App\Controller\Admin;

use App\Entity\Tarea;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TareaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tarea::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //Caampo ID sólo de lectura
            IdField::new('id')->hideOnForm(),
            IntegerField::new('prioridad','Prioridad'),
            TextField::new('nombre','Nombre'),
        ];
    }
    
}
