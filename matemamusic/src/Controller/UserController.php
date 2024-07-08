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

    #[Route('/users/{userId}/playlists', name: 'app_user_playlists')]
    public function playlistsForUser(int $userId): Response
    {
        $response = $this->client->request(
            'GET',
            "http://playlist-svc-nginx/user/$userId/playlists"
        );
        if (Response::HTTP_OK != $response->getStatusCode()) {
            return new JsonResponse(
                ['error' => 'Unable to fetch user playlists'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        $playlists = $response->toArray();

        foreach ($playlists['items'] as &$playlist) {
            foreach ($playlist['trackList'] as &$track) {
                $this->hydrateTrack($track);
            }
        }

        return new JsonResponse(
            [ 'playlists' => $playlists['items'] ],
            Response::HTTP_OK,
        );
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
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function hydrateTrack(array &$track): void
    {
        $response = $this->client->request(
            'GET',
            "http://catalog-svc-nginx/tracks/{$track['trackId']}"
        );
        if (Response::HTTP_OK != $response->getStatusCode()) {
            throw new \Exception("Unable to retrieve track #{$track['trackId']}");
        }

        $trackInfo = $response->toArray();

        foreach (['title', 'author', 'link'] as $index) {
            $track[$index] = $trackInfo[$index];
        }
    }
}
