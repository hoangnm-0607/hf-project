<?php

namespace App\Repository;

use App\Entity\Crondispatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Crondispatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crondispatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crondispatch[]    findAll()
 * @method Crondispatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrondispatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crondispatch::class);
    }
}
