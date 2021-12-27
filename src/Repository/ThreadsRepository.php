<?php

namespace App\Repository;

use App\Entity\Threads;
use App\Entity\Boards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Threads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Threads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Threads[]    findAll()
 * @method Threads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Threads::class);
    }

    /**
    * @return Threads[] Returns an array of Threads objects
    */

    public function findByBoard($boardName)
    {
        $query = $this->getEntityManager()->createQuery('SELECT t FROM App:Threads t WHERE t.board = (SELECT b.id FROM App:Boards b WHERE b.name = :boardName) ORDER BY t.created_at');
        $query->setParameter('boardName', $boardName);
        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Threads
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
