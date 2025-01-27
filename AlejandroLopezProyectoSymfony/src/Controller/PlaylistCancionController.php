<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Perfil;
use App\Entity\Playlist;
use App\Entity\Cancion;
use App\Entity\Estilo;
use App\Entity\PlaylistCancion;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistCancionController extends AbstractController
{
    #[Route('/crear/playlist_cancion', name: 'app_crear_playlist_cancion',methods:['GET'])]
    public function crearPlaylist(EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuario();
        $perfil = new Perfil();
        $playlist = new Playlist();
        $cancion = new Cancion();
        $genero = new Estilo();
        $perfil = new Perfil();
        $playlist_cancion = new PlaylistCancion();

        $perfil->setFoto("");
        $perfil->setDescripcion("Perfil 1");

        $usuario->setEmail("alh243@madrid.es");
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

        $playlist_cancion->setPlaylist($playlist);
        $playlist_cancion->setCancion($cancion);

        $entityManager->persist($perfil);              
        $entityManager->persist($usuario);
        $entityManager->persist($playlist);
        $entityManager->persist($cancion);
        $entityManager->persist($genero);
        $entityManager->persist($perfil);
        $entityManager->persist($playlist_cancion);

        $entityManager->flush();

        return new Response("Playlist Cancion guardada con éxito");
    }
}
