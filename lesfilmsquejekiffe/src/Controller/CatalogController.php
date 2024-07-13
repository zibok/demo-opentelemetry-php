<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CatalogController
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    #[Route(path: '/films/search', name: 'app_films_search')]
    public function searchFilms(Request $request): Response
    {
        $searchString = $request->query->get('search');

        $response = $this->client->request(
            'GET',
            'http://catalog-svc-nginx/search/films',
            [
                'headers' => ['Accept' => 'application/json'],
                'query' => ['search' => $searchString],
            ],
        );

        if (200 !== $response->getStatusCode()) {
            throw new \Exception("Falied to search films for {$searchString}");
        }

        return new JsonResponse(
            $response->toArray(),
            Response::HTTP_OK,
        );
    }
}
