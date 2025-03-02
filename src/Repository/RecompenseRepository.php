<?php

namespace App\Repository;

use App\Entity\Recompense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recompense>
 */
class RecompenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recompense::class);
    }

    /**
     * Find all active recompenses.
     *
     * @return Recompense[]
     */
    public function findActiveRecompenses(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :statut')
            ->setParameter('statut', true)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find recompenses by type.
     *
     * @param string $type The type of recompense to search for.
     * @return Recompense[]
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.type = :type')
            ->setParameter('type', $type)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find recompenses with points greater than or equal to a specified value.
     *
     * @param int $points The minimum points required.
     * @return Recompense[]
     */
    public function findRecompensesWithMinimumPoints(int $points): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.points_requis >= :points')
            ->setParameter('points', $points)
            ->orderBy('r.points_requis', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find recompenses created within a date range.
     *
     * @param \DateTimeInterface $startDate The start date.
     * @param \DateTimeInterface $endDate The end date.
     * @return Recompense[]
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.created_at BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}