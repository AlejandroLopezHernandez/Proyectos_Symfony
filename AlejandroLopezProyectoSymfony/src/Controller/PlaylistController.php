<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Perfil;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistController extends AbstractController
{
    #[Route('/crear/playlist', name: 'app_crear_playlist',methods:['GET'])]
    public function crearPlaylist(EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlist();

        $playlist->setNombre("Opera lirica");
        $playlist->setVisibilidad("Visible");
        $playlist->setLikes(50);
        
        $entityManager->persist($playlist);

        $entityManager->flush();
        return new Response("Playlist guardado con éxito");
    }
    /*#[Route('/playlist', name: 'music_playlist')]
    public function index(PlaylistRepository $repositorio):Response
    {
        $playlists = $repositorio->findAll();
        return $this->render('./play/play.html.twig',['playlists'=>$playlists]);
    }*/

    #[Route('/playlist', name: 'index_playlist')]
    public function index2(PlaylistRepository $repositorio2):JsonResponse
    {
        $playlistsobtenidas = $repositorio2->findAll();
        $playlistdisponibles = [];
        foreach ($playlistsobtenidas as $playlist){
            $playlistdisponibles[] = [
                'id'=>$playlist->getId(),
                'nombre'=>$playlist->getNombre(),
                'likes'=>$playlist->getLikes(),
                'visibilidad'=>$playlist->getVisibilidad()
            ]; 
        }
        return new JsonResponse($playlistdisponibles);
    }
}