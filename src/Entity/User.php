<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: 'email')]
#[UniqueEntity(fields: 'nickname')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[NotBlank]
    #[Email]
    private string $email;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[NotBlank]
    private string $nickname;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[NotBlank(groups: ['register'])]
    private ?string $plainPassword = null;

    /**
     * @var Collection<int, SmellyCode>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SmellyCode::class)]
    private Collection $smellyCodes;

    #[ORM\Column(nullable: true)]
    private ?string $avatar = null;

    #[Image(groups: ['profile'], maxSize: '1M')]
    private ?UploadedFile $avatarFile = null;

    public function __construct()
    {
        $this->smellyCodes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @return array<array-key, string>
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, SmellyCode>
     */
    public function getSmellyCodes(): Collection
    {
        return $this->smellyCodes;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getAvatarFile(): ?UploadedFile
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?UploadedFile $avatarFile): void
    {
        $this->avatarFile = $avatarFile;
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->nickname,
        ]);
    }

    /**
     * @param string $data
     */
    public function unserialize($data): void
    {
        /** @var array<int, string|int> $unserializedData */
        $unserializedData = unserialize($data);
        [
            $this->id,
            $this->email,
            $this->password,
            $this->nickname,
        ] = $unserializedData;
    }
}
