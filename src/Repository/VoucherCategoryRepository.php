<?php

namespace App\Repository;

use App\Entity\VoucherCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VoucherCategory>
 *
 * @method VoucherCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherCategory[]    findAll()
 * @method VoucherCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoucherCategory::class);
    }

//    /**
//     * @return VoucherCategory[] Returns an array of VoucherCategory objects
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

//    public function findOneBySomeField($value): ?VoucherCategory
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
