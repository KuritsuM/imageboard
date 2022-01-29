<?php

namespace App\Repository;

use App\Entity\Moderator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Moderator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moderator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moderator[]    findAll()
 * @method Moderator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moderator::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Moderator) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Moderator[] Returns an array of Moderator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByUsername($username): ?Moderator
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.username = :val')
            ->setParameter('val', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
