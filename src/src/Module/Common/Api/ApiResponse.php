<?php

declare(strict_types=1);

namespace App\Module\Common\Api;

final class ApiResponse
{
    public static function success(
        mixed $data = null,
        ?string $message = null,
        array $meta = []
    ): array {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        if ($meta !== []) {
            $response['meta'] = $meta;
        }

        return $response;
    }

    public static function error(
        string $code,
        string $message,
        array $details = []
    ): array {
        $response = [
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        if ($details !== []) {
            $response['error']['details'] = $details;
        }

        return $response;
    }
}