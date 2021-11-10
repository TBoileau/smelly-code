<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\SmellyCode as SmellyCodeDto;
use App\Entity\SmellyCode;

interface SmellyCodeFactoryInterface
{
    public function createFromDto(SmellyCodeDto $smellyCode): SmellyCode;
}
