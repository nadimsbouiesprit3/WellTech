<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Defi;
use App\Entity\Progression;
use App\Entity\Recompense;
use App\Entity\Coupon;
use App\Entity\Objective;
use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class UserProgressService
{
    private EntityManagerInterface $entityManager;
    private CouponRepository $couponRepository;

    public function __construct(EntityManagerInterface $entityManager, CouponRepository $couponRepository)
    {
        $this->entityManager = $entityManager;
        $this->couponRepository = $couponRepository;
    }

    // 1. Progress Tracking
    public function completeDefi(User $user, Defi $defi): void
    {
        $progression = new Progression();
        $progression->setUser($user);
        $progression->setDefi($defi);
        $progression->setStatut('Terminé');
        $progression->setDateCompletion(new \DateTime());

        $user->addPoints($defi->getPoints()); // Add points to the user

        $this->entityManager->persist($progression);
        $this->entityManager->flush();
    }

    // 2. Reward System
    public function redeemReward(User $user, Recompense $recompense): void
    {
        if ($user->getPoints() < $recompense->getPointsRequis()) {
            throw new Exception('Not enough points to redeem this reward.');
        }

        $user->deductPoints($recompense->getPointsRequis()); // Deduct points
        $this->entityManager->flush();
    }

    // 3. Coupon System
    public function applyCoupon(User $user, string $couponCode): void
    {
        $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);

        if (!$coupon || !$coupon->isActive()) {
            throw new Exception('Invalid or inactive coupon.');
        }

        // Apply the coupon logic (e.g., discount, unlock content)
        $user->applyCoupon($coupon);
        $this->entityManager->flush();
    }

    // 4. Objective System
    public function setObjective(User $user, Objective $objective): void
    {
        $user->addObjective($objective);
        $this->entityManager->flush();
    }
}