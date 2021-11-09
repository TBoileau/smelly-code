<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method          findAll()                                                                     array<int, Tag>
 * @method          findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Tag>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Tag>
 */
final class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function create(Tag $tag): void
    {
        $this->_em->persist($tag);
        $this->_em->flush($tag);
    }
}
