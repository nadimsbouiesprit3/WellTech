<?php

namespace App\Repository;

use App\Entity\Defi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Defi>
 */
class DefiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Defi::class);
    }

    /**
     * Find all active Defis.
     *
     * @return Defi[]
     */
    public function findActiveDefis(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->setParameter('statut', true)
            ->orderBy('d.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Defis by type.
     *
     * @param string $type The type of Defi to search for.
     * @return Defi[]
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.type = :type')
            ->setParameter('type', $type)
            ->orderBy('d.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Defis with points greater than or equal to a specified value.
     *
     * @param int $points The minimum points required.
     * @return Defi[]
     */
    public function findDefisWithMinimumPoints(int $points): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.points >= :points')
            ->setParameter('points', $points)
            ->orderBy('d.points', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Defis created within a date range.
     *
     * @param \DateTimeInterface $startDate The start date.
     * @param \DateTimeInterface $endDate The end date.
     * @return Defi[]
     */
    public function findDefisByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.created_at BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('d.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}