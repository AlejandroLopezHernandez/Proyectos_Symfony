<?php
namespace App\Controller;

use App\Entity\Cancion;
use App\Repository\CancionRepository;
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
    #[Route('/cancion', name: 'app_music')]
    public function index(CancionRepository $repositorio):Response
    {
        $canciones = $repositorio->findAll();
        return $this->render('./play/play.html.twig',['canciones'=>$canciones]);
    }
}