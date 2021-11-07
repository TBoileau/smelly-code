<?php

declare(strict_types=1);

namespace App\SmellyCode;

use App\Entity\SmellyCode;

interface SmellyCodeUrlGeneratorFactoryInterface
{
    public function generate(SmellyCode $smellyCode): string;
}
