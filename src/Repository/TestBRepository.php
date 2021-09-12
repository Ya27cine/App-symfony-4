<?php

namespace App\Repository;

use App\Entity\TestB;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestB|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestB|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestB[]    findAll()
 * @method TestB[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestBRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestB::class);
    }

    // /**
    //  * @return TestB[] Returns an array of TestB objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TestB
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
