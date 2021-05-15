<?php

namespace App\Repository;

use App\Entity\YouthPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method YouthPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method YouthPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method YouthPlayer[]    findAll()
 * @method YouthPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YouthPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YouthPlayer::class);
    }
}
