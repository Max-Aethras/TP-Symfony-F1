<?php

namespace App\Repository;

use App\Entity\Pilote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PiloteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pilote::class);
    }

    public function findByEcurie(int $ecurieId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.ecurie', 'e')
            ->where('e.id = :id')
            ->setParameter('id', $ecurieId)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActifs(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.statut = :statut')
            ->setParameter('statut', 'actif')
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findSuspendus(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.points < 1')
            ->orWhere('p.statut = :s')
            ->setParameter('s', 'suspendu')
            ->getQuery()
            ->getResult();
    }
}
