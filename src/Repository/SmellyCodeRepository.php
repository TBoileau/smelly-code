<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SmellyCode;
use App\Entity\User;
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

    /**
     * @return array<array-key, SmellyCode>
     */
    public function getTopSmellyCodes(): array
    {
        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('s')
            ->addSelect('u')
            ->addSelect('up')
            ->addSelect('down')
            ->addSelect('t')
            ->leftJoin('s.upVotes', 'up')
            ->leftJoin('s.downVotes', 'down')
            ->join('s.user', 'u')
            ->leftJoin('s.tags', 't')
            ->orderBy('COUNT(up) - COUNT(down)', 'desc')
            ->groupBy('s.id')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<array-key, SmellyCode>
     */
    public function getTopSmellyCodesByUser(User $user): array
    {
        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('s')
            ->addSelect('up')
            ->addSelect('down')
            ->addSelect('t')
            ->leftJoin('s.upVotes', 'up')
            ->leftJoin('s.downVotes', 'down')
            ->leftJoin('s.tags', 't')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->orderBy('COUNT(up) - COUNT(down)', 'desc')
            ->groupBy('s.id')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<array-key, SmellyCode> $smellyCodes
     */
    public function getRandomSmellyCode(array $smellyCodes, ?User $user): ?SmellyCode
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->join('s.user', 'u')
            ->addSelect('u')
            ->setMaxResults(1)
            ->orderBy('RAND()');

        $queryBuilder->andWhere('s NOT IN (:smellyCodes)')->setParameter('smellyCodes', [0] + $smellyCodes);

        if (null !== $user) {
            $queryBuilder->andWhere('u != :user')->setParameter('user', $user);
        }

        /* @phpstan-ignore-next-line */
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
