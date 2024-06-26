<?php

namespace App\Repository;

use App\Entity\Sponsoring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sponsoring>
 *
 * @method Sponsoring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sponsoring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sponsoring[]    findAll()
 * @method Sponsoring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SponsoringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponsoring::class);
    }

//    /**
//     * @return Sponsoring[] Returns an array of Sponsoring objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sponsoring
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
/**
     * Récupère tous les sponsors actifs.
     *
     * @return Sponsoring[] Returns an array of Sponsoring objects
     */
    public function findActiveSponsors(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.type = :type')
            ->setParameter('type', 'ACTIVE')
            ->getQuery()
            ->getResult();
    }

}
