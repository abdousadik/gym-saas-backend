<?php

declare(strict_types=1);

namespace App\Module\Common\EventListener;

use App\Module\Common\Api\ApiErrorCode;
use App\Module\Common\Api\ApiResponse;
use App\Module\Common\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

final class ApiExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException) {
            $event->setResponse(new JsonResponse(
                ApiResponse::error(
                    $exception->getErrorCode(),
                    $exception->getMessage(),
                    $exception->getDetails()
                ),
                $exception->getStatusCode()
            ));

            return;
        }

        if ($exception instanceof ValidationFailedException) {
            $details = [];

            foreach ($exception->getViolations() as $violation) {
                if (!$violation instanceof ConstraintViolationInterface) {
                    continue;
                }

                $details[] = [
                    'field' => (string) $violation->getPropertyPath(),
                    'message' => (string) $violation->getMessage(),
                ];
            }

            $event->setResponse(new JsonResponse(
                ApiResponse::error(
                    ApiErrorCode::VALIDATION_ERROR,
                    'Validation failed.',
                    $details
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY
            ));

            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();

            $event->setResponse(new JsonResponse(
                ApiResponse::error(
                    $this->mapHttpStatusToErrorCode($statusCode),
                    $exception->getMessage() !== '' ? $exception->getMessage() : 'HTTP error.'
                ),
                $statusCode
            ));

            return;
        }

        $event->setResponse(new JsonResponse(
            ApiResponse::error(
                ApiErrorCode::INTERNAL_ERROR,
                'An unexpected error occurred.'
            ),
            Response::HTTP_INTERNAL_SERVER_ERROR
        ));
    }

    private function mapHttpStatusToErrorCode(int $statusCode): string
    {
        return match ($statusCode) {
            Response::HTTP_BAD_REQUEST => ApiErrorCode::BAD_REQUEST,
            Response::HTTP_UNAUTHORIZED => ApiErrorCode::UNAUTHORIZED,
            Response::HTTP_FORBIDDEN => ApiErrorCode::FORBIDDEN,
            Response::HTTP_NOT_FOUND => ApiErrorCode::NOT_FOUND,
            Response::HTTP_METHOD_NOT_ALLOWED => ApiErrorCode::METHOD_NOT_ALLOWED,
            Response::HTTP_CONFLICT => ApiErrorCode::CONFLICT,
            default => ApiErrorCode::INTERNAL_ERROR,
        };
    }
}