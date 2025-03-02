<?php

namespace App\Repository;

use App\Entity\Objective;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Objective>
 */
class ObjectiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objective::class);
    }

    /**
     * Find objectives with points greater than or equal to a specified value.
     *
     * @param int $points The minimum points required.
     * @return Objective[]
     */
    public function findObjectivesWithMinimumPoints(int $points): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.points >= :points')
            ->setParameter('points', $points)
            ->orderBy('o.points', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find objectives by title (case-insensitive search).
     *
     * @param string $title The title to search for.
     * @return Objective[]
     */
    public function findByTitle(string $title): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('LOWER(o.title) LIKE LOWER(:title)')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('o.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find objectives created within a date range.
     *
     * @param \DateTimeInterface $startDate The start date.
     * @param \DateTimeInterface $endDate The end date.
     * @return Objective[]
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.created_at BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the top N objectives with the highest points.
     *
     * @param int $limit The maximum number of objectives to return.
     * @return Objective[]
     */
    public function findTopObjectives(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.points', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}