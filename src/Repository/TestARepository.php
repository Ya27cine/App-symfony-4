<?php

namespace App\Repository;

use App\Entity\TestA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestA|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestA|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestA[]    findAll()
 * @method TestA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestA::class);
    }

    // /**
    //  * @return TestA[] Returns an array of TestA objects
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
    public function findOneBySomeField($value): ?TestA
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
