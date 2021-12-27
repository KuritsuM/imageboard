<?php

namespace App\Repository;

use App\Entity\Boards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Boards|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boards|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boards[]    findAll()
 * @method Boards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boards::class);
    }

    public function findByBoardName(string $boardName) : Boards {
        $query = $this->getEntityManager()->createQuery('SELECT b FROM App:Boards b WHERE b.name = :boardName');
        $query->setParameter('boardName', $boardName);
        return $query->getResult()[0];
    }
    // /**
    //  * @return Boards[] Returns an array of Boards objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Boards
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
