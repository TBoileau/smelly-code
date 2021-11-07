<?php

declare(strict_types=1);

namespace App\UseCase\Skip;

use App\Entity\SmellyCode;
use Symfony\Component\HttpFoundation\RequestStack;

final class Skip implements SkipInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function __invoke(SmellyCode $smellyCode): void
    {
        /** @var array<array-key, SmellyCode> $smellyCodes */
        $smellyCodes = $this->requestStack->getSession()->get('smelly_codes', []);
        $smellyCodes[] = $smellyCode;
        $this->requestStack->getSession()->set('smelly_codes', $smellyCodes);
    }
}
