<?php

namespace App\Repository;

use App\Entity\TrickEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrickEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickEntity[]    findAll()
 * @method TrickEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickEntity::class);
    }

    // /**
    //  * @return TrickEntity[] Returns an array of TrickEntity objects
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
    public function findOneBySomeField($value): ?TrickEntity
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
