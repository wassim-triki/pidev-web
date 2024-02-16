<?php

namespace App\Repository;

use App\Entity\PostGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostGroup>
 *
 * @method PostGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostGroup[]    findAll()
 * @method PostGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostGroup::class);
    }

//    /**
//     * @return PostGroup[] Returns an array of PostGroup objects
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

//    public function findOneBySomeField($value): ?PostGroup
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
