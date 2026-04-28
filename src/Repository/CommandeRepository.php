<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @return Commande[]
     */
    public function findAllOrderedByDate(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'u')
            ->addSelect('u')
            ->orderBy('c.date_livraison', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Commande[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.menu', 'm')
            ->addSelect('m')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.date_commande', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
