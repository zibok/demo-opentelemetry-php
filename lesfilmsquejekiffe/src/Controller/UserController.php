<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

        if (Response::HTTP_OK != $response->getStatusCode()) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user list'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        $users = $response->toArray();

        return new JsonResponse(
            [ 'users' => $users['items'] ],
            Response::HTTP_OK
        );
    }

    #[Route('/users/{userId}/favlists', name: 'app_user_favlists')]
    public function favlistsForUser(int $userId): Response
    {
        $response = $this->client->request(
            'GET',
            "http://favlist-svc-nginx/user/$userId/favlists"
        );
        if (Response::HTTP_OK != $response->getStatusCode()) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user favlists'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        $favlists = $response->toArray();

        foreach ($favlists['items'] as &$favlist) {
            foreach ($favlist['filmList'] as &$film) {
                $this->hydrateFilm($film);
            }
        }

        return new JsonResponse(
            [ 'favlists' => $favlists['items'] ],
            Response::HTTP_OK,
        );
    }

    #[Route('/users/{userId}/createfavlist', name: 'app_user_favlist_create', methods: ['POST'])]
    public function createPlaylistForUser(int $userId, Request $request): Response
    {
        $payload = $request->getContent();
        $data = json_decode($payload);

        $response = $this->client->request(
            'POST',
            'http://favlist-svc-nginx/create',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => ['ownerId' => $userId, 'name' => $data->name],
            ]
        );
        if (204 != $response->getStatusCode()) {
            return new JsonResponse(
                ['error' => 'Unable to create favlist'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function hydrateFilm(array &$film): void
    {
        $response = $this->client->request(
            'GET',
            "http://catalog-svc-nginx/films/{$film['filmId']}"
        );
        if (Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Exception("Unable to retrieve film #{$film['filmId']}");
        }

        $filmInfo = $response->toArray();

        foreach (['title', 'author', 'genre'] as $index) {
            $film[$index] = $filmInfo[$index];
        }
    }
}
