<?php

declare(strict_types=1);

namespace App\SmellyCode;

use App\Entity\Carbon;
use App\Entity\SmellyCode;

final class CarbonUrlGenerator implements UrlGeneratorInterface
{
    /**
     * @param Carbon $smellyCode
     */
    public function generate(SmellyCode $smellyCode): string
    {
        $url = $smellyCode->getUrl();

        preg_match('/^https:\/\/carbon\.now\.sh\/(\w+)$/', $url, $matches);

        return $matches[1];
    }

    public static function getType(): string
    {
        return Carbon::class;
    }
}
