<?php
namespace App\Controller;

use App\Entity\Cancion;
use App\Repository\CancionRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class CancionController extends AbstractController
{
    #[Route('/crear/cancion', name: 'app_cancion')]
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
    #[Route('/cancion/{tituloCancion}', name: 'play_music',methods:['GET'])]
    public function ReproducirMusica(string $tituloCancion,EntityManagerInterface $entityManager):Response{
        $repositorio = $entityManager->getRepository(Cancion::class);
        $cancion = $repositorio->findOneBy(['titulo'=>$tituloCancion]);
        if(!$cancion){
            return new Response("Error 1, canción no encontrada",404);
        }
        $nombreArchivo = trim($cancion->getArchivo());
        var_dump($nombreArchivo.'<br>'); 
        $directorioMusica = $this->getParameter('kernel.project_dir').'/public/songs/';
        $ruta = $directorioMusica.$nombreArchivo.".mp3";

        if(!file_exists(realpath($ruta))){
            return new Response('Error 2, archivo no encontrado',404);
        }
        $response = new BinaryFileResponse($ruta);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE);
        $response->headers->set('Content-Type','audio/mpeg');
        return $response;
    }
    #[Route('/cancion', name: 'index_music')]
    public function index(CancionRepository $repositorio1, PlaylistRepository $repositorio2):Response
    {
        $canciones = $repositorio1->findAll();
        $playlists = $repositorio2->findAll();

        return $this->render('./play/play.html.twig',['canciones'=>$canciones,'playlists'=>$playlists]);
    }

    #[Route('/cancionesJSON', name: 'obtener_canciones',methods:['GET'])]
    public function index3(CancionRepository $repositorio):JsonResponse
    {
        $canciones_obtenidas = $repositorio->findAll();
        $canciones_disponibles = [];
        foreach ($canciones_obtenidas as $cancion){
            $canciones_disponibles[] = [
                'titulo'=>$cancion->getTitulo(),
                'autor'=>$cancion->getAutor()   
            ]; 
        }
        return new JsonResponse($canciones_disponibles);
    }

    #[Route('/playlist/{id}', name: 'playlist_detalle',methods:['GET'])]
    public function verPlaylist(int $id, PlaylistRepository $playlistRepository):Response{
        $playlist = $playlistRepository->find($id);
        if (!$playlist) {
            throw $this->createNotFoundException('La playlist no existe.');
        }
    
        return $this->render('./play/play.html.twig', [
            'canciones' => $playlist->getPlaylistCanciones(), 
            'playlists' => [$playlist]
        ]);
    }
}