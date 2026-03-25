<?php

declare(strict_types=1);

namespace App\Module\Common\Exception;

use Exception;

class ApiException extends Exception
{
    public function __construct(
        private readonly string $errorCode,
        string $message,
        private readonly int $statusCode = 400,
        private readonly array $details = []
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}