<?php

namespace App\Repository;

use App\Entity\Progression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Progression>
 */
class ProgressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Progression::class);
    }

    /**
     * Find all progressions for a specific user.
     *
     * @param int $userId The ID of the user.
     * @return Progression[]
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.date_completion', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all progressions for a specific Defi.
     *
     * @param int $defiId The ID of the Defi.
     * @return Progression[]
     */
    public function findByDefi(int $defiId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.defi = :defiId')
            ->setParameter('defiId', $defiId)
            ->orderBy('p.date_completion', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all completed progressions.
     *
     * @return Progression[]
     */
    public function findCompletedProgressions(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.statut = :statut')
            ->setParameter('statut', 'completed')
            ->orderBy('p.date_completion', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find progressions created within a date range.
     *
     * @param \DateTimeInterface $startDate The start date.
     * @param \DateTimeInterface $endDate The end date.
     * @return Progression[]
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.created_at BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}