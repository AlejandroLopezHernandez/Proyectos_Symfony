<?php

namespace App\Form;

use App\Entity\Playlist;
use App\Entity\Cancion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('visibilidad', CheckboxType::class, [
                'required' => false,
            ])
            ->add('playlistCanciones', EntityType::class, [
                'class' => Cancion::class,
                'choice_label' => 'titulo',
                'multiple' => true,
                'expanded' => true, //Muestra checkboxes
                'label' => 'Selecciona canciones para tu playlist',
                'mapped' => false,
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
