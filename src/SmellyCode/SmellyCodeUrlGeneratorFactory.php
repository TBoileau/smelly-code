<?php

declare(strict_types=1);

namespace App\SmellyCode;

use App\Entity\SmellyCode;

final class SmellyCodeUrlGeneratorFactory implements SmellyCodeUrlGeneratorFactoryInterface
{
    /**
     * @param iterable<UrlGeneratorInterface> $urlGenerators
     */
    public function __construct(private iterable $urlGenerators)
    {
    }

    public function generate(SmellyCode $smellyCode): string
    {
        foreach ($this->urlGenerators as $urlGenerator) {
            if ($urlGenerator::getType() === $smellyCode::class) {
                return $urlGenerator->generate($smellyCode);
            }
        }

        throw new \LogicException(sprintf('Try to implement an UrlGenerator for %s.', $smellyCode::class));
    }
}
