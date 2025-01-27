<?php

namespace App\Controller;

use App\Entity\Cancion;
use App\Entity\Estilo;
use App\Entity\Perfil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CancionController extends AbstractController
{
    #[Route('/crear/cancion', name: 'app_cancion')]
    public function crearCancion(EntityManagerInterface $entityManager): Response
    {
        $cancion = new Cancion();
        $genero = new Estilo();
        $perfil = new Perfil();

        $perfil->setFoto("");
        $perfil->setDescripcion("Perfil 1");

        $genero->setNombre("Balada romántica");
        $genero->setDescripcion("Balada romántica");
        $genero->setPerfil($perfil);

        $cancion->setAutor("Andrea Bocelli");
        $cancion->setTitulo("Con te partirò");
        $cancion->setGenero($genero);
        $cancion->setAlbum("Bocelli");
        $cancion->setReproducciones(5);
        $cancion->setLikes(5);
        $cancion->setDuracion(4);

        $entityManager->persist($cancion);
        $entityManager->persist($genero);
        $entityManager->persist($perfil);

        $entityManager->flush();

        return new Response("Canción guardada con éxito");

    }
}
