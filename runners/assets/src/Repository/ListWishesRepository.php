<?php

namespace App\Repository;

use App\Entity\ListWishes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListWishes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListWishes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListWishes[]    findAll()
 * @method ListWishes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListWishesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListWishes::class);
    }

    // /**
    //  * @return ListWishes[] Returns an array of ListWishes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListWishes
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
