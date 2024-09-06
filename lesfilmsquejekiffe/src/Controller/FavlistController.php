<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FavlistController {
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
    ) {
    }

    #[Route(path:"/favlists/{favlistId}/films", name:"app_favlist_add_films", methods: ['POST'])]
    public function addFilmsToFavlist(int $favlistId, Request $request): Response {
        $response = $this->client->request(
            'POST',
            "http://favlist-svc-nginx/favlist/{$favlistId}/films", 
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => ['filmIds' => $request->toArray()['filmIds']],
            ],
        );

        if (Response::HTTP_NO_CONTENT != $response->getStatusCode()) {
            $responseObj = $response->toArray(false);
            return new JsonResponse(
                [ 'error' => "Unable to add films to favlist {$favlistId}", 'details' => $responseObj["error"] ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new Response("", Response::HTTP_NO_CONTENT);
    }
}
