<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

final class FlashMessageFactory implements FlashMessageFactoryInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function send(string $status, string $message): void
    {
        if (!in_array($status, [
            self::STATUS_ERROR,
            self::STATUS_INFO,
            self::STATUS_SUCCESS,
            self::STATUS_WARNING,
        ])) {
            throw new \Exception('This status does not exist.'); // @codeCoverageIgnore
        }

        /** @var Session $session */
        $session = $this->requestStack->getSession();

        $session->getFlashBag()->add($status, $message);
    }
}
