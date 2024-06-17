<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    #[Route('/users/list', name: 'app_user_list')]
    public function index(): Response
    {
        $response = $this->client->request(
            'GET',
            'http://user-svc-nginx/list'
        );

        if ($response->getStatusCode() != 200) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user list'],
                500,
            );
        }

        $result = [];

        $users = $response->toArray();

        return new JsonResponse([
            'users' => $users['items'],
        ], 200);
    }
}
