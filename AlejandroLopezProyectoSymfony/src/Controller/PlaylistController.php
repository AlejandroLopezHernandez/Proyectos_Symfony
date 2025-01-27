<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Perfil;
use App\Entity\Playlist;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistController extends AbstractController
{
    #[Route('/crear/playlist', name: 'app_crear_playlist',methods:['GET'])]
    public function crearPlaylist(EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuario();
        $perfil = new Perfil();
        $playlist = new Playlist();

        $usuario->setEmail("alh243@hotmail.es");
        $usuario->setPassword("1234");
        $usuario->setNombre("Gaby");
        $usuario->setFechaNacimiento(new DateTime('1995-03-10'));

        
        $perfil->setFoto("");
        $perfil->setDescripcion("Perfil 5");
        $usuario->setPerfil($perfil);

        $playlist->setNombre("Opera lirica");
        $playlist->setVisibilidad("Visible");
        $playlist->setReproducciones(5);
        $playlist->setLikes(50);
        $playlist->setPropietario($usuario);
        
        $entityManager->persist($perfil);              
        $entityManager->persist($usuario);
        $entityManager->persist($playlist);

        $entityManager->flush();

        return new Response("Playlist guardada con éxito");
    }
}
