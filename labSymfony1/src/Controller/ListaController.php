<?php

namespace App\Controller;
use App\Entity\Lista;
use App\Entity\Cancion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ListaController extends AbstractController
{
    #[Route('/lista', name: 'app_lista')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ListaController.php',
        ]);
    }
    #[Route('/lista/canciones', name: 'app_lista')]
    public function guardarCancion(EntityManagerInterface $entityManager):Response{
        $lista1 = new Lista();
        $lista1->setNombre('Lista de Reproducción');

        $cancion1 = new Cancion();
        $cancion1->setNombre("Oroscopo");
        $cancion1->setAutor("Calcutta");
        $cancion1->setDisco("Mainstream");
        $cancion1->setGenero("Pop italiano");
        $cancion1->setDuracion(138);

        $lista1->setCanciones($cancion1);

        $entityManager->persist($lista1);
        $entityManager->persist($cancion1);
        $entityManager->flush();

        return new Response(
            "Guardada la canción con ID:".$cancion1->getId()
            ." en una lista con id".$lista1->getId()
        );
    }
}
