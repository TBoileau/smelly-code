<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SmellyCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmellyCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmellyCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method                 findAll()                                                                     array<int, SmellyCode>
 * @method                 findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, SmellyCode>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<SmellyCode>
 */
final class SmellyCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmellyCode::class);
    }
}