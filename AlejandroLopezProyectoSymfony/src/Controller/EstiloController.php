<?php

namespace App\Controller;

use App\Entity\Perfil;
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
        $perfil = new Perfil();
        
        $perfil->setFoto("");
        $perfil->setDescripcion("Perfil 2");

        $estilo->setId(1);
        $estilo->setNombre("Ópera lírica");
        $estilo->setDescripcion("Ópera lírica italiana");
        $estilo->setPerfil($perfil);
        
        $entityManager->persist($estilo);
        $entityManager->persist($perfil);

        $entityManager->flush();

        return new Response("Estilo guardada con éxito");

    }
}
