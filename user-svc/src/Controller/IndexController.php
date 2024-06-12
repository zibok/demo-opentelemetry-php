<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return new JsonResponse([
            'name' => 'user-svc',
            'version' => '1.0.0',
            'apis' => [
                'list' => '/list',
                'create' => '/create',
            ],
        ]);
    }
}
