<?php

namespace App\Repository;

use App\Entity\Ecurie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EcurieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecurie::class);
    }

    public function findByNomInsensitive(string $nom): ?Ecurie
    {
        return $this->createQueryBuilder('e')
            ->where('LOWER(e.nom) = LOWER(:nom)')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllWithPilotes(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.pilotes', 'p')
            ->addSelect('p')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
