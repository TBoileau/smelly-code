<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

#[ORM\Entity]
class Carbon extends SmellyCode
{
    #[ORM\Column]
    #[NotBlank]
    #[Url]
    #[Regex(pattern: '/^https:\/\/carbon.now.sh\/\w+$/')]
    private string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
