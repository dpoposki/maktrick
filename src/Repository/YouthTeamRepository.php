<?php

namespace App\Repository;

use App\Entity\YouthTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method YouthTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method YouthTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method YouthTeam[]    findAll()
 * @method YouthTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YouthTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YouthTeam::class);
    }
}
