<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method           findAll()                                                                     array<int, User>
 * @method           findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, User>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<User>
 */
final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return array<array-key, User>
     */
    public function getTopUsers(): array
    {
        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('u')
            ->addSelect('s')
            ->addSelect('up')
            ->addSelect('down')
            ->addSelect('t')
            ->join('u.smellyCodes', 's')
            ->leftJoin('s.upVotes', 'up')
            ->leftJoin('s.downVotes', 'down')
            ->leftJoin('s.tags', 't')
            ->orderBy('COUNT(up) - COUNT(down)', 'desc')
            ->groupBy('s.id')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }
}
