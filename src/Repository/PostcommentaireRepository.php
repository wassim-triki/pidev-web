<?php

namespace App\Repository;

use App\Entity\Postcommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postcommentaire>
 *
 * @method Postcommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postcommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postcommentaire[]    findAll()
 * @method Postcommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostcommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postcommentaire::class);
    }

//    /**
//     * @return Postcommentaire[] Returns an array of Postcommentaire objects
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

//    public function findOneBySomeField($value): ?Postcommentaire
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
