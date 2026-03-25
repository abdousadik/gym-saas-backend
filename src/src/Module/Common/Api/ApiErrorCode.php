<?php

declare(strict_types=1);

namespace App\Module\Common\Api;

final class ApiErrorCode
{
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const UNAUTHORIZED = 'UNAUTHORIZED';
    public const FORBIDDEN = 'FORBIDDEN';
    public const NOT_FOUND = 'NOT_FOUND';
    public const METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';
    public const CONFLICT = 'CONFLICT';
    public const INTERNAL_ERROR = 'INTERNAL_ERROR';
}