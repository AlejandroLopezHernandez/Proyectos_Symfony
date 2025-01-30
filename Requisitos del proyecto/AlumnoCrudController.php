<?php

namespace App\Controller\Admin;

use App\Entity\Alumno;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AlumnoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Alumno::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            //Para números hay que mostrar NumberField
            TextField::new('Nombre'),
            //se usa el campo se abajo cuando vamos a mostrar disitintos campos, es decir, una colección
            AssociationField::new('Cursos')
        ];
    }
}
