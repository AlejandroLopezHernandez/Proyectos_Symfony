<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use App\Repository\PlaylistCancionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EstadisticasController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    #[Route('/manager', name: 'estadisticas')]
    public function index(PlaylistCancionRepository $repositorio, UsuarioRepository $repositorio2, Security $security): Response
    {
        $usuario = $security->getUser();
        $datos_reproducciones = $repositorio->obtenerReproduccionesPorPlaylist();
        $datos_likes = $repositorio->obtenerLikesPorPlaylist();
        $canciones_mas_reproduc = $repositorio->obtenerCancionesMasReproducidas();
        $datos_usuario_edad = $repositorio2->clasificarUsuariosXedad();

        $this->logger->info("El manager ha accecido al dashboard", [
            'usuario' => $usuario->getUserIdentifier(),
            'action' => 'check',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        return $this->render('estadisticas/estadisticas.html.twig', [
            'datos_reproducciones' => $datos_reproducciones,
            'datos_likes' => $datos_likes,
            'canciones_mas_reprod' => $canciones_mas_reproduc,
            'edad_datos' => $datos_usuario_edad
        ]);
    }
    //Por cada dato que querramos mostrar, hay que crear unaa función
    #[Route('/manager/datos_reproducciones', name: 'estadisticas_datos')]
    public function obtenerDatosReproducciones(PlaylistCancionRepository $repositorio): Response
    {
        $datos_reproducciones = $repositorio->obtenerReproduccionesPorPlaylist();
        return $this->json($datos_reproducciones);
    }
    #[Route('/manager/datos_likes', name: 'likes_datos')]
    public function obtenerDatosLikes(PlaylistCancionRepository $repositorio): Response
    {
        $datos_likes = $repositorio->obtenerLikesPorPlaylist();
        return $this->json($datos_likes);
    }
    #[Route('/manager/datos_reproducciones_cancion', name: 'canciones_reprod_datos')]
    public function ObtenerCancionesMasReproducidas(PlaylistCancionRepository $repositorio): Response
    {
        $datos_canciones = $repositorio->obtenerCancionesMasReproducidas();
        return $this->json($datos_canciones);
    }
    #[Route('/manager/datos_edad', name: 'edad_datos')]
    public function ClasificarFranjaEdad(UsuarioRepository $repositorio): Response
    {
        $datos_usuario_edad = $repositorio->clasificarUsuariosXedad();
        return $this->json($datos_usuario_edad);
    }
}
