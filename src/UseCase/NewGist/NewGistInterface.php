<?php

declare(strict_types=1);

namespace App\UseCase\NewGist;

use App\Entity\Gist;

interface NewGistInterface
{
    public function __invoke(Gist $gist): void;
}
