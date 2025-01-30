<?php
namespace App\Controller;

use App\Entity\Cancion;
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

        $cancion->setAutor("Andrea Bocelli");
        $cancion->setTitulo("Con te partiro");
        $cancion->setAlbum("Bocelli");
        $cancion->setLikes(5);
        $cancion->setDuracion(4);

        $entityManager->persist($cancion);
        

        $entityManager->flush();

        return new Response("Canción guardada con éxito");
    }
}