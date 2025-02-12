<?php

namespace App\Controller;

use App\Entity\Perfil;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class PerfilController extends AbstractController
{
    #[Route('/crear/perfil', name: 'app_cancion')]
    public function crearPerfil(EntityManagerInterface $entityManager): Response
    {
        $perfil = new Perfil();
        
        $perfil->setFoto("");
        $perfil->setDescripcion("Perfil 1");

        $entityManager->persist($perfil);

        $entityManager->flush();
        return new Response("Perfil guardado con éxito");
    }
}