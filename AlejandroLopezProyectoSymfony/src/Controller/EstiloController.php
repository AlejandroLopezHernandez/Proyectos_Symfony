<?php

namespace App\Controller;

use App\Entity\Estilo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class EstiloController extends AbstractController
{
    #[Route('/crear/estilo', name: 'app_cancion')]
    public function crearEstilo(EntityManagerInterface $entityManager): Response
    {
        $estilo = new Estilo();

        $estilo->setId(1);
        $estilo->setNombre("Opera lirica");
        $estilo->setDescripcion("Opera lirica italiana");
        
        $entityManager->persist($estilo);


        $entityManager->flush();

        return new Response("Estilo guardado con éxito");
    }
}
