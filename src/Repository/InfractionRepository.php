<?php

namespace App\Repository;

use App\Entity\Infraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InfractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infraction::class);
    }

    public function search(?int $ecurieId = null, ?int $piloteId = null, ?\DateTimeInterface $date = null): array
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.ecurie', 'e')
            ->leftJoin('i.pilote', 'p')
            ->addSelect('e', 'p');

        if ($ecurieId) {
            $qb->andWhere('e.id = :ecurie')->setParameter('ecurie', $ecurieId);
        }

        if ($piloteId) {
            $qb->andWhere('p.id = :pilote')->setParameter('pilote', $piloteId);
        }

        if ($date) {
            $start = (clone $date)->setTime(0, 0);
            $end = (clone $date)->setTime(23, 59, 59);
            $qb->andWhere('i.dateInfraction BETWEEN :start AND :end')
               ->setParameter('start', $start)
               ->setParameter('end', $end);
        }

        return $qb->orderBy('i.dateInfraction', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByPilote(int $piloteId): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.pilote', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $piloteId)
            ->orderBy('i.dateInfraction', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByEcurie(int $ecurieId): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.ecurie', 'e')
            ->where('e.id = :id')
            ->setParameter('id', $ecurieId)
            ->orderBy('i.dateInfraction', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
