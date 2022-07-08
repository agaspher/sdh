<?php

declare(strict_types=1);

namespace App\Logger;

use Monolog\ResettableInterface;

class TokenRequestProcessor implements ResettableInterface
{
    private string $token;

    public function __construct()
    {
        $this->token = uniqid('', true);
    }

    public function __invoke(array $record): array
    {
        $record['extra']['token'] = $this->token;

        return $record;
    }

    public function reset(): void
    {
        $this->token = uniqid('', true);
    }
}