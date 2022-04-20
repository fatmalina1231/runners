<?php

namespace App\Repository;

use App\Entity\Wishes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wishes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wishes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wishes[]    findAll()
 * @method Wishes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wishes::class);
    }

    // /**
    //  * @return Wishes[] Returns an array of Wishes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wishes
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
