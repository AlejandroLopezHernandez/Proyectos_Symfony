<?php

namespace App\Controller;

use App\Entity\Tarea;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\Routing\Attribute\Route;

class TareaController extends AbstractController
{
    #[Route('/tarea', name: 'app_tarea')]
    public function createTarea(EntityManagerInterface $entityManager):Response
    {
        $tarea = new Tarea();
        $tarea->setTarea("Tarea 1");
        $tarea->setNombre("Diagrama E/R");
        $tarea->setPrioridad(1);
        $tarea->setDescripcion("Diagrama E/R del proyecto");
        $entityManager->persist($tarea);
        $entityManager->flush();
        return new Response("Tareas guardadas");
    }
}
