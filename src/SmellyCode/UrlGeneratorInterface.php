<?php

declare(strict_types=1);

namespace App\SmellyCode;

use App\Entity\SmellyCode;

interface UrlGeneratorInterface
{
    public function generate(SmellyCode $smellyCode): string;

    /**
     * @return class-string
     */
    public static function getType(): string;
}
