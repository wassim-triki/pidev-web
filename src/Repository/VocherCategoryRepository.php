<?php

namespace App\Repository;

use App\Entity\VocherCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VocherCategory>
 *
 * @method VocherCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method VocherCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method VocherCategory[]    findAll()
 * @method VocherCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VocherCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VocherCategory::class);
    }

//    /**
//     * @return VocherCategory[] Returns an array of VocherCategory objects
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

//    public function findOneBySomeField($value): ?VocherCategory
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
