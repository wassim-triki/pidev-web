<?php

namespace App\Repository;

use App\Entity\Avertissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avertissement>
 *
 * @method Avertissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avertissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avertissement[]    findAll()
 * @method Avertissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvertissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avertissement::class);
    }
    public function findByQuery($query)
    {
        return $this->createQueryBuilder('a')
            ->where('a.ReportedUsername LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
    public function countAvertissementsByRaison()
    {
        return $this->createQueryBuilder('a')
            ->select('a.raison, COUNT(a.id) as nombre')
            ->groupBy('a.raison')
            ->getQuery()
            ->getResult();
    }
    public function countraisoninappropriatecontent():int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as nombre')
            ->where('a.raison = :raison')
            ->setParameter('raison', 'inappropriate content')
            ->getQuery()
            ->getSingleScalarResult();
           
    }
    public function countraisoninappropriatecontent2():int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as nombre')
            ->where('a.raison = :raison')
            ->setParameter('raison', 'violation of the rules of the platform')
            ->getQuery()
            ->getSingleScalarResult();
           
    }
    public function countraisoninappropriatecontent3():int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as nombre')
            ->where('a.raison = :raison')
            ->setParameter('raison', 'other reasons')
            ->getQuery()
            ->getSingleScalarResult();
           
    }
    
 



//    /**
//     * @return Avertissement[] Returns an array of Avertissement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Avertissement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
