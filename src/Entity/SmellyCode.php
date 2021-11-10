<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SmellyCodeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: SmellyCodeRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'gist' => Gist::class,
    'carbon' => Carbon::class,
])]
abstract class SmellyCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column]
    #[NotBlank]
    protected string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    protected DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    protected DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'smellyCodes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinTable(name: 'smelly_code_up_votes')]
    private Collection $upVotes;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinTable(name: 'smelly_code_down_votes')]
    private Collection $downVotes;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'smelly_code_tags')]
    private Collection $tags;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->upVotes = new ArrayCollection();
        $this->downVotes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUpVotes(): Collection
    {
        return $this->upVotes;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDownVotes(): Collection
    {
        return $this->downVotes;
    }

    public function canVote(User $user): bool
    {
        return !(new ArrayCollection(
            array_merge(
                $this->upVotes->toArray(),
                $this->downVotes->toArray(),
                [$this->user]
            )
        ))->contains($user);
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection<int, Tag> $tags
     */
    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
