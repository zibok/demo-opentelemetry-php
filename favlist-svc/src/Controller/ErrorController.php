<?php

declare(strict_types=1);

namespace App\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ErrorController {
    function show(\Throwable $exception, LoggerInterface $logger): Response {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR; 
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        return new JsonResponse(
            [
                'error' => $exception->getMessage(),
            ],
            $statusCode,
        );
    }
}
