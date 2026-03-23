<?php

namespace App\Module\Plan\Repository;

use App\Module\Plan\Entity\MembershipPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MembershipPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembershipPlan::class);
    }
}