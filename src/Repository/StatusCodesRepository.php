<?php

namespace App\Repository;

use App\Entity\StatusCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatusCodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusCodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusCodes[]    findAll()
 * @method StatusCodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusCodesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusCodes::class);
    }

    // /**
    //  * @return StatusCodes[] Returns an array of StatusCodes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatusCodes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
