<?php

namespace App\Controller;

use App\Entity\Cancion;
use App\Entity\Usuario;
use App\Entity\Perfil;
use App\Entity\Playlist;
use App\Entity\PlaylistCancion;
use App\Entity\UsuarioPlaylist;
use App\Form\PlaylistType;
use App\Repository\CancionRepository;
use App\Repository\PlaylistRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Json;



final class PlaylistController extends AbstractController
{
    #[Route('/user/crear_playlist', name: 'crear_playlist', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function crearPlaylist(EntityManagerInterface $entityManager, Request $request, CancionRepository $repositorioCancion, Security $security): Response
    {
        $usuario = $security->getUser();
        $playlist = new Playlist();
        $playlist->setLikes(0); // Establecemos un valor predeterminado para likes
        $playlist->setPropietario($usuario);

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cancionesIds = $request->request->all('canciones');
            if (!is_array($cancionesIds)) {
                $cancionesIds = [];
            }
            foreach ($cancionesIds as $cancionId) {
                $cancion = $repositorioCancion->find($cancionId);
                if ($cancion) {
                    $playlistCancion = new PlaylistCancion();
                    $playlistCancion->setCancion($cancion);
                    $playlistCancion->setPlaylist($playlist);
                    $entityManager->persist($playlistCancion);
                }
            }

            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('main_page');
            //return new JsonResponse(['message' => 'Playlist creada con éxito', 'playlist_id' => $playlist->getId()]);
        }
        $canciones = $repositorioCancion->findAll();

        return $this->render('playlist/crear.html.twig', [
            'form' => $form->createView(),
            'canciones' => $canciones,
        ]);
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
    #[Route('/user/buscarPlaylist/{nombre}', name: 'buscar_playlist', methods: ['GET'])]
    public function buscarPlaylistXNombre(PlaylistRepository $repositorio, string $nombre): JsonResponse
    {
        if (!$nombre) {
            return new JsonResponse(['error' => 'No se proporcionó un nombre'], 400);
        }

        $playlistsobtenidas = $repositorio->createQueryBuilder('p')
            ->where('p.nombre LIKE :nombre')
            ->setParameter('nombre', '%' . $nombre . '%')
            ->getQuery()
            ->getResult();

        if (empty($playlistsobtenidas)) {
            return new JsonResponse(['mensaje' => 'No se encontraron playlist con ese nombre'], 404);
        }
        $playlistdisponibles = [];
        foreach ($playlistsobtenidas as $playlist) {
            $playlistdisponibles[] = [
                'id' => $playlist->getId(),
                'nombre' => $playlist->getNombre(),
                'likes' => $playlist->getLikes(),
            ];
        }
        return new JsonResponse($playlistdisponibles);
    }
    #[Route('/user/mis_playlists', name: 'mis_playlists', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function misPlaylists(EntityManagerInterface $entityManager, PlaylistRepository $repositorioPlaylist): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Debes inciar sesión para poder ver tus playlists'], 403);
        }
        $playlistPropias = $repositorioPlaylist->findBy(['propietario' => $user]);
        $usuarioPlaylistRepo = $entityManager->getRepository(\App\Entity\UsuarioPlaylist::class);
        $playlistsCompartidas = $usuarioPlaylistRepo->findBy(['usuario' => $user]);

        $playlistsCompartidasArray = array_map(fn($usuarioPlaylist) => $usuarioPlaylist->getPlaylist(), $playlistsCompartidas);

        $todasLasPlaylists = array_unique(array_merge($playlistPropias, $playlistsCompartidasArray), SORT_REGULAR);
        $misPlaylistsArray = array_map(fn($playlist) => [
            'id' => $playlist->getId(),
            'nombre' => $playlist->getNombre(),
            'likes' => $playlist->getLikes(),
            'visibilidad' => $playlist->getVisibilidad(),
        ], $todasLasPlaylists);
        return new JsonResponse([
            'success' => true,
            'mis_playlists' => $misPlaylistsArray
        ]);
    }
}
