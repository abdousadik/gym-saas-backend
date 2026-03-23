<?php

namespace App\Module\Gym\Repository;


use App\Module\Gym\Entity\Gym;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GymRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gym::class);
    }
}