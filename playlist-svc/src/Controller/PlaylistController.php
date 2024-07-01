<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\VO\Track;
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

    #[Route('/user/{userId}/playlists', name: 'app_playlist_list', methods: ['GET'])]
    public function list(int $userId): Response
    {
        $playlists = $this->playlistRepository->findPlaylistsByOwner($userId);

        $result = [];
        foreach ($playlists as $playlist) {
            $result[] = $this->normalizePlaylist($playlist);
        }

        return new JsonResponse(
            ['items' => $result],
            200
        );
    }

    #[Route('/create', name: 'app_playlist_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $payload = $request->getContent();
        $data = json_decode($payload);
        $this->playlistRepository->createNewPlaylist($data->ownerId, $data->name);

        return new Response('', 204);
    }

    #[Route('/playlist/{playlistId}', name: 'app_playlist_get', methods: ['GET'])]
    public function getPlaylistById(int $playlistId): Response
    {
        $playlist = $this->playlistRepository->findPlaylistById($playlistId);

        return new JsonResponse(
            $this->normalizePlaylist($playlist),
            200,
        );
    }

    #[Route('/playlist/{playlistId}/tracks', name: 'app_playlist_addtracks', methods: ['POST'])]
    public function addTracks(int $playlistId, Request $request): Response
    {
        $payload = json_decode($request->getContent());
        $trackIdsToAdd = $payload->trackIds;

        $tracksToAdd = array_map(
            fn ($trackId) => new Track($trackId),
            $trackIdsToAdd,
        );

        $playlist = $this->playlistRepository->findPlaylistById($playlistId);

        $playlist->setTrackList(
            array_merge(
                $playlist->getTrackList(),
                array_map(
                    fn ($trackId) => new Track($trackId),
                    $trackIdsToAdd,
                ),
            )
        );

        $this->playlistRepository->save($playlist);

        return new Response('', 204);
    }

    private function normalizePlaylist(Playlist $playlist): array
    {
        return [
            'id' => $playlist->getId(),
            'name' => $playlist->getName(),
            'trackList' => array_map(
                fn ($item) => ['id' => $item->id],
                $playlist->getTrackList(),
            ),
        ];
    }
}
