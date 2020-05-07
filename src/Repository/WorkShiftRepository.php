<?php

namespace App\Repository;

use App\Entity\WorkShift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkShift|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkShift|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkShift[]    findAll()
 * @method WorkShift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkShiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkShift::class);
    }

    // /**
    //  * @return WorkShift[] Returns an array of WorkShift objects
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
    public function findOneBySomeField($value): ?WorkShift
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
