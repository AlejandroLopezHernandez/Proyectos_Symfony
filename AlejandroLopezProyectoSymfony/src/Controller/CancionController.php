<?php

namespace App\Controller;

use App\Entity\Cancion;
use App\Repository\CancionRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class CancionController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/user/CrearCancion', name: 'app_cancion')]
    public function crearCancion(EntityManagerInterface $entityManager): Response
    {
        $cancion = new Cancion();

        $cancion->setAutor("Andrea Bocelli");
        $cancion->setTitulo("Con te partiro");
        $cancion->setAlbum("Bocelli");
        $cancion->setLikes(5);
        $cancion->setDuracion(4);

        $entityManager->persist($cancion);


        $entityManager->flush();

        return new Response("Canción guardada con éxito");
    }
    #[Route('/user/cancion/{tituloCancion}', name: 'play_music', methods: ['GET'])]
    public function ReproducirMusica(string $tituloCancion, EntityManagerInterface $entityManager, Security $security): Response
    {
        $usuario = $security->getUser();
        $repositorio = $entityManager->getRepository(Cancion::class);
        $cancion = $repositorio->findOneBy(['titulo' => $tituloCancion]);
        if (!$cancion) {
            return new Response("Error 1, canción no encontrada", 404);
        }
        $nombreArchivo = trim($cancion->getArchivo());
        $directorioMusica = $this->getParameter('kernel.project_dir') . '/public/songs/';
        $ruta = $directorioMusica . $nombreArchivo . ".mp3";

        if (!file_exists(realpath($ruta))) {
            return new Response('Error 2, archivo no encontrado', 404);
        }
        $response = new BinaryFileResponse($ruta);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);
        $response->headers->set('Content-Type', 'audio/mpeg');

        if (!$usuario) {
            $this->logger->info("Se ha reproducido una canción", [
                'cancion' => $cancion->getTitulo(),
                'action' => 'play',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->logger->info("Se ha reproducido una canción", [
                'usuario' => $usuario->getUserIdentifier(),
                'cancion' => $cancion->getTitulo(),
                'action' => 'play',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }


        return $response;
    }
    #[Route('/main', name: 'main_page')]
    public function index(CancionRepository $repositorio1, PlaylistRepository $repositorio2): Response
    {
        $canciones = $repositorio1->findAll();
        $playlists = $repositorio2->findAll();

        return $this->render('./play/play.html.twig', ['canciones' => $canciones, 'playlists' => $playlists]);
    }

    #[Route('/user/cancionesJSON', name: 'obtener_canciones', methods: ['GET'])]
    public function index3(CancionRepository $repositorio): JsonResponse
    {
        $canciones_obtenidas = $repositorio->findAll();
        $canciones_disponibles = [];
        foreach ($canciones_obtenidas as $cancion) {
            $canciones_disponibles[] = [
                'titulo' => $cancion->getTitulo(),
                'autor' => $cancion->getAutor()
            ];
        }
        return new JsonResponse($canciones_disponibles);
    }

    #[Route('/user/playlist/{id}', name: 'playlist_detalle', methods: ['GET'])]
    public function verPlaylist(int $id, PlaylistRepository $playlistRepository): Response
    {
        $playlist = $playlistRepository->find($id);
        if (!$playlist) {
            throw $this->createNotFoundException('La playlist no existe.');
        }

        return $this->render('./play/play.html.twig', [
            'canciones' => $playlist->getPlaylistCanciones(),
            'playlists' => [$playlist]
        ]);
    }
    #[Route('/user/buscarCancion/{titulo}', name: 'buscar_cancion', methods: ['GET'])]
    public function BuscarCancion(string $titulo, CancionRepository $repositorio, Security $security): JsonResponse
    {
        $usuario = $security->getUser();
        if (!$titulo) {
            return new JsonResponse(['error' => 'No se proporcionó un título'], 400);
        }
        $canciones = $repositorio->createQueryBuilder('c')
            ->where('c.titulo LIKE :titulo')
            ->setParameter('titulo', '%' . $titulo . '%')
            ->getQuery()
            ->getResult();
        if (empty($canciones)) {
            return new JsonResponse(['mensaje' => 'No se encontraron canciones con ese título'], 404);
        }
        $canciones_disponibles = [];
        foreach ($canciones as $cancion) {
            $canciones_disponibles[] = [
                'titulo' => $cancion->getTitulo(),
                'autor' => $cancion->getAutor()
            ];
        }
        if (!$usuario) {
            $this->logger->info("Se ha buscado una canción", [
                'cancion' => $cancion->getTitulo(),
                'action' => 'search',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->logger->info("Se ha buscado una canción", [
                'usuario' => $usuario->getUserIdentifier(),
                'cancion' => $cancion->getTitulo(),
                'action' => 'search',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }
        return new JsonResponse($canciones_disponibles);
    }
}
