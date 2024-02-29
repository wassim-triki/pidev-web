<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function showpostid($user)
    {
        $rsm = new ResultSetMapping();
        // Define your mapping here if needed

        $query = $this->getEntityManager()->createNativeQuery('SELECT * FROM your_table WHERE user = :user', $rsm);
        $query->setParameter('user', $user);

        return $query->getResult();
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function advancedSearch(array $criteria)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
        SELECT p
        FROM App\Entity\Post p
        WHERE p.titre LIKE :titre
    ');

        foreach ($criteria as $field => $value) {
            switch ($field) {
                case 'titre':
                    $query->setParameter('titre', '%' . $value . '%');
                    break;
                    // Add more cases for other fields as needed
            }
        }

        return $query->getResult();
    }

    public function findByType($type)
    {
        return $this->createQueryBuilder('p')
            ->where('p.type LIKE :type')
            ->setParameter('type', '%' . $type . '%')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
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

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
