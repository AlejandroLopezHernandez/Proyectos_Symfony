<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Perfil;
use App\Entity\UsuarioPlaylist;
use App\Entity\Playlist;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class UsuarioPlaylistController extends AbstractController
{
    #[Route('/crear/usuario_playlist', name: 'app_cancion')]
    public function crearUsuarioPlaylist(EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuario();
        $perfil = new Perfil();
        $usuario_playlist = new UsuarioPlaylist();
        $playlist = new Playlist();

        $usuario->setEmail("ejemplo@hotmail.es");
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
        
        $usuario_playlist->setReproducida(1);
        $usuario_playlist->setUsuario($usuario);
        $usuario_playlist->setPlaylist($playlist);

        $entityManager->persist($perfil);              
        $entityManager->persist($usuario);
        $entityManager->persist($playlist);
        $entityManager->persist($usuario_playlist);

        $entityManager->flush();

        return new Response("Usuario Playlist guardada con éxito");
    }
}
