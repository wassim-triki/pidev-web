<?php

namespace App\Repository;

use App\Entity\Vocher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vocher>
 *
 * @method Vocher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vocher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vocher[]    findAll()
 * @method Vocher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VocherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vocher::class);
    }

//    /**
//     * @return Vocher[] Returns an array of Vocher objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vocher
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
