<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\SmellyCode;
use App\SmellyCode\SmellyCodeUrlGeneratorFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TwigFilters extends AbstractExtension
{
    public function __construct(private SmellyCodeUrlGeneratorFactoryInterface $smellyCodeUrlGeneratorFactory)
    {
    }

    /**
     * @return array<array-key, TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('smelly_code', [$this, 'smellyCode']),
        ];
    }

    public function smellyCode(SmellyCode $smellyCode): string
    {
        return $this->smellyCodeUrlGeneratorFactory->generate($smellyCode);
    }
}
