<?php

declare(strict_types=1);

namespace App\Module\Common\Controller;

use App\Module\Common\Api\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController extends AbstractController
{
    protected function success(
        mixed $data = null,
        ?string $message = null,
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return $this->json(
            ApiResponse::success($data, $message, $meta),
            $status
        );
    }

    protected function error(
        string $code,
        string $message,
        int $status = 400,
        array $details = []
    ): JsonResponse {
        return $this->json(
            ApiResponse::error($code, $message, $details),
            $status
        );
    }
}