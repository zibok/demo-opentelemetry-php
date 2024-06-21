<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $users = $response->toArray();

        return new JsonResponse([
            'users' => $users['items'],
        ], 200);
    }

    #[Route('/users/{userId}/playlists', name: 'app_user_playlists')]
    public function playlistsForUser(int $userId): Response
    {
        $response = $this->client->request(
            'GET',
            "http://playlist-svc-nginx/user/$userId/playlists"
        );
        if ($response->getStatusCode() != 200) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user playlists'],
                500,
            );
        }

        $playlists = $response->toArray();

        return new JsonResponse([
            'playlists' => $playlists['items'],
        ], 200);
    }

    #[Route('/users/{userId}/createplaylist', name: 'app_user_playlist_create', methods: ['POST'])]
    public function createPlaylistForUser(int $userId, Request $request): Response
    {
        $payload = $request->getContent();
        $data = json_decode($payload);

        $response = $this->client->request(
            'POST',
            "http://playlist-svc-nginx/create",
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => ['ownerId' => $userId, 'name' => $data->name],
            ]
        );
        if ($response->getStatusCode() != 204) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user playlists'],
                500,
            );
        }

        return new Response("", 204);
    }
}
