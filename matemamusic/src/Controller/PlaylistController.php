<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PlaylistController {
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    #[Route(path:"/playlists/{playlistId}/tracks", name:"app_playlist_add_tracks", methods: ['POST'])]
    public function addTracksToPlaylist(int $playlistId, Request $request): Response {
        $response = $this->client->request(
            'POST',
            "http://playlist-svc-nginx/playlist/{$playlistId}/tracks", 
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => ['trackIds' => $request->toArray()['trackIds']],
            ],
        );

        if (Response::HTTP_NO_CONTENT != $response->getStatusCode()) {
            return new JsonResponse(
                [ 'error' => "Unable to add track to playlist {$playlistId}" ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new Response("", Response::HTTP_NO_CONTENT);
    }
}
