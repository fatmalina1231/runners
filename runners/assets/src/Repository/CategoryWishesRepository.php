<?php

namespace App\Repository;

use App\Entity\CategoryWishes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryWishes|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryWishes|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryWishes[]    findAll()
 * @method CategoryWishes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryWishesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryWishes::class);
    }

    // /**
    //  * @return CategoryWishes[] Returns an array of CategoryWishes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryWishes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
