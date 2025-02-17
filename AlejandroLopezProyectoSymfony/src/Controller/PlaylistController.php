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
    #[Route('/user/crear/playlist', name: 'app_crear_playlist', methods: ['GET'])]
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

    #[Route('/user/playlist', name: 'index_playlist')]
    public function index2(PlaylistRepository $repositorio2): JsonResponse
    {
        $playlistsobtenidas = $repositorio2->findAll();
        $playlistdisponibles = [];
        foreach ($playlistsobtenidas as $playlist) {
            $playlistdisponibles[] = [
                'id' => $playlist->getId(),
                'nombre' => $playlist->getNombre(),
                'likes' => $playlist->getLikes(),
                'visibilidad' => $playlist->getVisibilidad()
            ];
        }
        return new JsonResponse($playlistdisponibles);
    }
    #[Route('/user/CancionesPlaylist/{tituloPlaylist}', name: 'canciones_playlist', methods: ['GET'])]
    public function CancionesPlaylist(EntityManagerInterface $entityManager, string $tituloPlaylist): JsonResponse
    {
        try {
            $RepositorioPlaylist = $entityManager->getRepository(Playlist::class);
            $playlist = $RepositorioPlaylist->findOneBy(['nombre' => $tituloPlaylist]);
            if (!$playlist) {
                return new JsonResponse(['error' => 'Playlist no encontrada'], 404);
            }
            $playlistCannciones = $playlist->getPlaylistCanciones();
            $canciones = [];
            foreach ($playlistCannciones as $p_c) {
                $cancion = $p_c->getCancion();
                $canciones[] = [
                    'titulo' => $cancion->getTitulo(),
                    'autor' => $cancion->getAutor(),
                    'ruta' => $this->getParameter('kernel.project_dir') . '/songs/' . $cancion->getArchivo() . '.mp3'
                ];
            }
            return new JsonResponse($canciones);
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}
