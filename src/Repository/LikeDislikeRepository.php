<?php

namespace App\Repository;

use App\Entity\LikeDislike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeDislikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeDislike::class);
    }

    public function countLikes(int $articleId): int
    {
        return $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.article = :articleId')
            ->andWhere('l.type = :like')
            ->setParameter('articleId', $articleId)
            ->setParameter('like', 'like')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countDislikes(int $articleId): int
    {
        return $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.article = :articleId')
            ->andWhere('l.type = :dislike')
            ->setParameter('articleId', $articleId)
            ->setParameter('dislike', 'dislike')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findUserReaction(int $userId, int $articleId): ?LikeDislike
    {
        return $this->createQueryBuilder('l')
            ->where('l.user = :userId')
            ->andWhere('l.article = :articleId')
            ->setParameter('userId', $userId)
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
