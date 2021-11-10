<?php

declare(strict_types=1);

namespace App\UseCase\UpdateProfile;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

final class UpdateProfile implements UpdateProfileInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $uploadDir
    ) {
    }

    public function __invoke(User $user): void
    {
        if (null !== $user->getAvatarFile()) {
            /** @var UploadedFile $file */
            $file = $user->getAvatarFile();

            $user->setAvatar(
                sprintf(
                    '%s.%s',
                    Uuid::v4(),
                    $file->getClientOriginalExtension()
                )
            );

            $file->move($this->uploadDir, $user->getAvatar());
        }

        $this->entityManager->flush();
    }
}
