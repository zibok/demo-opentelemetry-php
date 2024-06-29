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

        if (200 != $response->getStatusCode()) {
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
        if (200 != $response->getStatusCode()) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user playlists'],
                500,
            );
        }

        $playlists = $response->toArray();

        foreach ($playlists['items'] as &$playlist) {
            foreach ($playlist['trackList'] as &$track) {
                $this->hydrateTrack($track);
            }
        }

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
            'http://playlist-svc-nginx/create',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => ['ownerId' => $userId, 'name' => $data->name],
            ]
        );
        if (204 != $response->getStatusCode()) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user playlists'],
                500,
            );
        }

        return new Response('', 204);
    }

    private function hydrateTrack(array &$track): void
    {
        $response = $this->client->request(
            'GET',
            "http://catalog-svc-nginx/tracks/{$track['id']}"
        );
        if (200 != $response->getStatusCode()) {
            throw new \Exception("Unable to retrieve track #{$track['id']}");
        }

        $trackInfo = $response->toArray();

        foreach (['title', 'author', 'link'] as $index) {
            $track[$index] = $trackInfo[$index];
        }
    }
}
