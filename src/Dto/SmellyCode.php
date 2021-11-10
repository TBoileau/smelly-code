<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Tag;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class SmellyCode
{
    #[NotBlank]
    #[Regex(pattern: '/^https:\/\/(gist\.github\.com\/.*|carbon\.now\.sh)\/\w+$/')]
    public string $url;

    #[NotBlank]
    public string $name;

    /**
     * @var array<array-key, Tag>
     */
    public array $tags = [];
}
