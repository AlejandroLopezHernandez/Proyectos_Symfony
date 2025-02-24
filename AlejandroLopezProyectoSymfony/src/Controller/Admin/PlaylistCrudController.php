<?php

namespace App\Controller\Admin;

use App\Entity\Cancion;
use App\Entity\Playlist;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlaylistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Playlist::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nombre'),
            BooleanField::new('visibilidad'),
            NumberField::new('likes'),
            AssociationField::new('propietario', 'Propietario')
                ->setFormTypeOption('by_reference', true),
            CollectionField::new('playlistCanciones', 'Canciones')
                ->useEntryCrudForm(PlaylistCancionCrudController::class),
        ];
    }
}
