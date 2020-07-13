<?php

namespace App\Repository;

use App\Entity\EducationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EducationEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method EducationEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method EducationEvent[]    findAll()
 * @method EducationEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducationEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EducationEvent::class);
    }

    // /**
    //  * @return EducationEvent[] Returns an array of EducationEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EducationEvent
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
