<?php

namespace App\Repository;

use App\Entity\ListAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListAccess[]    findAll()
 * @method ListAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListAccess::class);
    }

    // /**
    //  * @return ListAccess[] Returns an array of ListAccess objects
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
    public function findOneBySomeField($value): ?ListAccess
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
