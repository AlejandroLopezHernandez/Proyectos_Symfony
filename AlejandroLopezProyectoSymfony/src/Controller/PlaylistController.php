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
use Psr\Log\LoggerInterface;
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
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    #[Route('/user/crear_playlist', name: 'crear_playlist', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    /* EntityManager: interacturar con la BBDD
       Request: Contiene datos de formularios y de peticiones GET/POST
       Security: obtner información sobre el usuario identificado  */
    public function crearPlaylist(EntityManagerInterface $entityManager, Request $request, CancionRepository $repositorioCancion, Security $security): Response
    {
        //Obtenemos el usuario actual y la playlist, asignamos a una playlist un usuario
        $usuario = $security->getUser();
        $playlist = new Playlist();
        $playlist->setLikes(0); // Establecemos un valor predeterminado para likes, este campo es obligatorio
        $playlist->setPropietario($usuario);
        //Creamos el formulario basado en Playlist Type, usamos la clase playlist
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        //Si el formulario se envía y es válido, hacemos lo siguiente
        if ($form->isSubmitted() && $form->isValid()) {
            //Obtenemos todas las canciones seleccionadas
            $canciones = $request->request->all('canciones');
            //Nos aseguramos que las canciones sean un array
            if (!is_array($canciones)) {
                $canciones = [];
            }
            foreach ($canciones as $cancion) {
                //Recorremos las canciones con un bucle, luego la encontramos
                $cancion = $repositorioCancion->find($cancion);
                if ($cancion) {
                    /*Ya que Playlist y Cancion es una N:M tenemos que usar la clase
                    PlaylistCancion para establecer la asociación 
                    Creamos una Playlist, y a PlaylistCancion le asociamos una playlist
                    y una canción */
                    $playlistCancion = new PlaylistCancion();
                    $playlistCancion->setCancion($cancion);
                    $playlistCancion->setPlaylist($playlist);
                    $entityManager->persist($playlistCancion);
                }
            }
            //Guardamos la playlist en la BBDD
            $entityManager->persist($playlist);
            $entityManager->flush();

            $this->logger->info("Playlist creada", [
                'usuario' => $usuario->getUserIdentifier(),
                'nombre' => $playlist->getNombre(),
                'action' => 'create',
                'timestamp' => date('Y-m-d H:i:s')
            ]);

            return $this->redirectToRoute('main_page');
        }
        $canciones = $repositorioCancion->findAll();
        //Mostrar el formulario si no se ha enviado
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
    public function index2(PlaylistRepository $repositorio2, Security $security): JsonResponse
    {
        $usuario = $security->getUser();
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
        $this->logger->info("El usuario ha accedido a la playlist", [
            'usuario' => $usuario->getUserIdentifier(),
            'nombre' => $playlist->getNombre(),
            'action' => 'access',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        return new JsonResponse($playlistdisponibles);
    }
    #[Route('/user/CancionesPlaylist/{tituloPlaylist}', name: 'canciones_playlist', methods: ['GET'])]
    public function CancionesPlaylist(EntityManagerInterface $entityManager, string $tituloPlaylist, Security $security): JsonResponse
    {
        try {
            $usuario = $security->getUser();
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
            $this->logger->info("El usuario ha accedido a las canciones de la playlist", [
                'usuario' => $usuario->getUserIdentifier(),
                'nombre' => $playlist->getNombre(),
                'action' => 'access',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            return new JsonResponse($canciones);
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
    #[Route('/user/buscarPlaylist/{nombre}', name: 'buscar_playlist', methods: ['GET'])]
    public function buscarPlaylistXNombre(PlaylistRepository $repositorio, string $nombre, Security $security): JsonResponse
    {
        $usuario = $security->getUser();
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
        $this->logger->info("El usuario ha accedido a esta playlist", [
            'usuario' => $usuario->getUserIdentifier(),
            'nombre' => $playlist->getNombre(),
            'action' => 'access',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        return new JsonResponse($playlistdisponibles);
    }
    #[Route('/user/mis_playlists', name: 'mis_playlists', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function misPlaylists(EntityManagerInterface $entityManager, PlaylistRepository $repositorioPlaylist, Security $security): JsonResponse
    {
        //Obtenemos el usuario actual, si no existe, devuelve una respuesta JSON
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Debes inciar sesión para poder ver tus playlists'], 403);
        }
        //Buscamos en el repositorio de Playlists las playlist del usuario actual
        $playlistPropias = $repositorioPlaylist->findBy(['propietario' => $user]);
        //Obtenemos el repositorio de la entidad UsuarioPlaylist
        $usuarioPlaylistRepo = $entityManager->getRepository(\App\Entity\UsuarioPlaylist::class);
        //Buscamos las playlists compartidas del usuario actual
        $playlistsCompartidas = $usuarioPlaylistRepo->findBy(['usuario' => $user]);
        //Con array_map extraemos sólo las playlists
        $playlistsCompartidasArray = array_map(fn($usuarioPlaylist) => $usuarioPlaylist->getPlaylist(), $playlistsCompartidas);
        //Combinamos los dos arrays anteriores con array_merge y con array_map eliminamos duplicados
        $todasLasPlaylists = array_unique(array_merge($playlistPropias, $playlistsCompartidasArray), SORT_REGULAR);
        //Formateamos en JSON
        $misPlaylistsArray = array_map(fn($playlist) => [
            'id' => $playlist->getId(),
            'nombre' => $playlist->getNombre(),
            'likes' => $playlist->getLikes(),
            'visibilidad' => $playlist->getVisibilidad(),
        ], $todasLasPlaylists);

        $this->logger->info("El usuario ha accedido sus playlists", [
            'usuario' => $user->getUserIdentifier(),
            'action' => 'access',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        //Devolvemos la respuesta
        return new JsonResponse([
            'success' => true,
            'mis_playlists' => $misPlaylistsArray
        ]);
    }
}
