<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Usuario) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function loadUsuerByIdentifier(string $userNameOrEmail)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\Usuario u
            WHERE u.nombre = :query
            OR u.email = :query'
        )
            ->setParameter('query', $userNameOrEmail)
            ->getOneOrNullResult();
    }

    /*public function clasificarUsuariosXedad(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.nombre AS usuario, u.fechaNacimiento, 
                 FLOOR(DATEDIFF(CURRENT_DATE(), u.fechaNacimiento) / 365) AS edad')
            ->addSelect(
                "CASE 
                WHEN FLOOR(DATEDIFF(CURRENT_DATE(), u.fechaNacimiento) / 365) < 18 THEN 'Menor de 18'
                WHEN FLOOR(DATEDIFF(CURRENT_DATE(), u.fechaNacimiento) / 365) BETWEEN 18 AND 30 THEN '18-30 años'
                WHEN FLOOR(DATEDIFF(CURRENT_DATE(), u.fechaNacimiento) / 365) BETWEEN 31 AND 40 THEN '31-40 años'
                WHEN FLOOR(DATEDIFF(CURRENT_DATE(), u.fechaNacimiento) / 365) BETWEEN 41 AND 50 THEN '41-50 años'
                ELSE 'Más de 50 años'
            END AS rango_edad"
            )
            ->orderBy('edad', 'ASC')
            ->getQuery()
            ->getResult();
    }*/

    public function clasificarUsuariosXedad(): array
    {
        $usuarios = $this->createQueryBuilder('u')
            ->select('u.id, u.nombre AS usuario,u.fechaNacimiento AS fecha_nacimiento')
            ->getQuery()
            ->getResult();

        $usuarios_clasificados = [];

        foreach ($usuarios as $usuario) {
            $fecha_nacimiento = $usuario['fecha_nacimiento'];
            $edad = $this->calcularEdad($fecha_nacimiento);
            $randoEdad = $this->clasificarEdad($edad);

            $usuarios_clasificados[] = [
                'usuario' => $usuario['usuario'],
                'edad' => $edad,
                'rango_edad' => $randoEdad
            ];
        }
        return $usuarios_clasificados;
    }
    private function calcularEdad($fecha_nacimiento)
    {
        if (!$fecha_nacimiento instanceof \DateTime) {
            $fecha_nacimiento = new \DateTime($fecha_nacimiento);
        }
        $hoy = new \DateTime();
        $edad = $hoy->diff($fecha_nacimiento);
        return $edad->y;
    }
    private function clasificarEdad($edad)
    {
        if ($edad < 18) {
            return 'Menor de 18';
        } elseif ($edad >= 18 && $edad <= 30) {
            return '18-30 años';
        } elseif ($edad >= 31 && $edad <= 40) {
            return '31-40 años';
        } elseif ($edad >= 41 && $edad <= 50) {
            return '41-50 años';
        } else {
            return 'Más de 50 años';
        }
    }
}
    


    //    /**
    //     * @return Usuario[] Returns an array of Usuario objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Usuario
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
