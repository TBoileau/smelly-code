<?php

declare(strict_types=1);

namespace App\SmellyCode;

use App\Entity\Gist;
use App\Entity\SmellyCode;

final class GistUrlGenerator implements UrlGeneratorInterface
{
    /**
     * @param Gist $smellyCode
     */
    public function generate(SmellyCode $smellyCode): string
    {
        $url = $smellyCode->getUrl();

        preg_match('/^https:\/\/gist\.github\.com\/.*\/(\w+)$/', $url, $matches);

        return $matches[1];
    }

    public static function getType(): string
    {
        return Gist::class;
    }
}
