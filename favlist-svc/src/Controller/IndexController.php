<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return new JsonResponse([
            'name' => 'favlist-svc',
            'version' => '1.0.0',
            'apis' => [
                'list' => '/user/{}/list',
                'create' => '/user/{}/create',
            ],
        ]);
    }
}
