<?php

namespace App\Repository;

use App\Entity\PlaylistCancion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlaylistCancion>
 */
class PlaylistCancionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, PlaylistCancion::class);
  }
  //Aquí tenemos que sacar los métodos SQL para obtener los resultados
  public function obtenerReproduccionesPorPlaylist(): array
  {
    return $this->createQueryBuilder('pc')->select('p.nombre AS playlist, pc.reproducciones
          AS totalReproducciones')
      ->join('pc.playlist', 'p')
      ->groupBy('p.id', 'pc.reproducciones')
      ->getQuery()
      ->getResult();
  }
  public function obtenerLikesPorPlaylist(): array
  {
    return $this->createQueryBuilder('pc')->select('p.nombre AS playlist, p.likes
          AS totalLikes')
      ->join('pc.playlist', 'p')
      ->groupBy('p.id')
      ->getQuery()
      ->getResult();
  }
  public function obtenerCancionesMasReproducidas(): array
  {
    return $this->createQueryBuilder('pc')
      ->select('c.titulo AS cancion, SUM(pc.reproducciones) AS reproduccionesXcancion')
      ->join('pc.cancion', 'c')
      ->groupBy('c.id')
      ->orderBy('reproduccionesXcancion', 'DESC')
      ->getQuery()
      ->getResult();
  }

  //    /**
  //     * @return PlaylistCancion[] Returns an array of PlaylistCancion objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('p.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?PlaylistCancion
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
