<?php

namespace App\Controller;

use App\Entity\Usuario;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class UsuarioController extends AbstractController
{
    #[Route('/crear/usuario', name: 'app_cancion')]
    public function crearUsuario(EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuario();

        $usuario->setId(1);
        $usuario->setEmail("alex1234@hotmail.es");
        $usuario->setPassword("1234");
        $usuario->setNombre("Alex");
        $usuario->setFechaNacimiento(new DateTime('1995-03-16'));
                     
        $entityManager->persist($usuario);

        $entityManager->flush();

        return new Response("Usuario guardado con éxito");
    }
}