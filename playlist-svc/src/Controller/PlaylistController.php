<?php

namespace App\Controller;

use App\Repository\PlaylistRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistController
{
    public function __construct(private PlaylistRepositoryInterface $playlistRepository)
    {
    }

    #[Route("/user/{userId}/playlists", name: "app_playlist_list", methods: ["GET"])]
    public function list(int $userId): Response
    {
        $playlists = $this->playlistRepository->findPlaylistsByOwner($userId);

        $result = [];
        foreach ($playlists as $playlist) {
            $result[] = [
                'id' => $playlist->getId(),
                'name' => $playlist->getName(),
            ];
        }

        return new JsonResponse(
            ['items' => $result],
            200
        );
    }

    #[Route('/create', name:'app_playlist_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $payload = $request->getContent();
        $data = json_decode($payload);
        $this->playlistRepository->createNewPlaylist($data->ownerId, $data->name);

        return new Response('',204);
    }
}
