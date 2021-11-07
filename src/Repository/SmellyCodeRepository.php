<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SmellyCode;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
            $queryBuilder
                ->andWhere('u != :user')
                ->setParameter('user', $user)
                ->leftJoin('s.upVotes', 'up', Join::WITH, 'up != :user')
                ->leftJoin('s.downVotes', 'down', Join::WITH, 'down != :user');
        }

        /* @phpstan-ignore-next-line */
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
