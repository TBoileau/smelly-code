<?php

declare(strict_types=1);

namespace App\Factory;

interface FlashMessageFactoryInterface
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_WARNING = 'warning';
    public const STATUS_INFO = 'info';
    public const STATUS_ERROR = 'error';

    public function send(string $status, string $message): void;
}
