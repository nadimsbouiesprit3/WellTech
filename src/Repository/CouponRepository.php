<?php

namespace App\Repository;

use App\Entity\Coupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coupon>
 */
class CouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    /**
     * Find all active coupons.
     *
     * @return Coupon[]
     */
    public function findActiveCoupons(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find coupons with a discount within a specified range.
     *
     * @param float $minDiscount The minimum discount.
     * @param float $maxDiscount The maximum discount.
     * @return Coupon[]
     */
    public function findByDiscountRange(float $minDiscount, float $maxDiscount): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.discount BETWEEN :minDiscount AND :maxDiscount')
            ->setParameter('minDiscount', $minDiscount)
            ->setParameter('maxDiscount', $maxDiscount)
            ->orderBy('c.discount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find coupons that expire after a specific date.
     *
     * @param \DateTimeInterface $expiresAfter The expiration date.
     * @return Coupon[]
     */
    public function findCouponsExpiringAfter(\DateTimeInterface $expiresAfter): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.expiresAt > :expiresAfter')
            ->setParameter('expiresAfter', $expiresAfter)
            ->orderBy('c.expiresAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a coupon by its code.
     *
     * @param string $code The coupon code.
     * @return Coupon|null
     */
    public function findOneByCode(string $code): ?Coupon
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }
}